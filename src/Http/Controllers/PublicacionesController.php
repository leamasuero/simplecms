<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Http\Request;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Middleware\CanViewPublicacion;
use Lebenlabs\SimpleCMS\Http\Middleware\PublicacionExiste;
use Lebenlabs\SimpleCMS\Http\Requests\StorePublicacionRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdatePublicacionRequest;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\SimpleCMS;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class PublicacionesController extends Controller
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SimpleStorageService
     */
    private $storage;

    /**
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(EntityManager $em, SimpleCMS $simpleCMSProvider, SimpleStorageService $storage)
    {
        $this->em = $em;

        // Get connection to use transaction
        $this->connection = $this->em->getConnection();

        // Register services
        $this->simpleCMSProvider = $simpleCMSProvider;
        $this->storage = $storage;

        // Register middleware
        $this->middleware('web');

        $this->middleware(CanManagePublicaciones::class, ['only' => ['edit', 'update', 'create', 'store', 'destroy', 'index']]);

        //Chequea que los parametros de publicacion para ver si existe y sino retorna error
        $this->middleware(PublicacionExiste::class, ['only' => ['edit', 'update', 'destroy']]);

        //Chequea que los parametros de publicacion para ver si existe y sino retorna error
        $this->middleware(CanViewPublicacion::class, ['only' => ['publicShow']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $q = $request->get('q', null);
        $privada = $request->get('privada', null);

        if ($privada != null) {
            $publicaciones = $this->simpleCMSProvider->buscarPublicacionesByPrivada($q, $privada, 20);
        } else {
            $publicaciones = $this->simpleCMSProvider->buscarPublicaciones($q, 20);
        }

        $publicaciones
            ->appends('q', $q)
            ->appends('privada', $privada);

        if ($q) {
            flash(trans('Lebenlabs/SimpleCMS::publicaciones.search_result', ['total' => $publicaciones->total()]))->success();
        }

        return view('Lebenlabs/SimpleCMS::Publicaciones.index', compact('publicaciones', 'q'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $publicacion = new Publicacion;

        $categorias = $this->simpleCMSProvider->listarCategoriasPublicacion();

        return view('Lebenlabs/SimpleCMS::Publicaciones.create', compact('publicacion', 'categorias'));
    }

    /**
     * @param StorePublicacionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function store(StorePublicacionRequest $request)
    {
        // Si se esta almacenando como borrador este parametro viene en 1
        $saveAsDraft = boolval($request->get('save_as_draft', 0));

        $publicacion = new Publicacion();
        $categoria = $this->simpleCMSProvider->findCategoria($request->get('categoria'));

        $publicacion->setTitulo($request->get('titulo'))
            ->setExtracto($request->get('extracto'))
            ->setCuerpo($request->get('cuerpo'))
            ->setFechaPublicacion($request->get('fecha_publicacion'))
            ->setDestacada(boolval($request->get('destacada', false)))
            ->setPrivada(boolval($request->get('privada', false)))
            ->setPublicada(boolval($request->get('publicada', false)))
            ->setCategoria($categoria);

        // En caso de que sea borrador quitamos la marca de publicada
        if ($saveAsDraft) {
            $publicacion->setPublicada(false);
        }

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarPublicacion($publicacion);
            $this->connection->commit();

            if ($saveAsDraft) {
                flash(trans('Lebenlabs/SimpleCMS::publicaciones.draft_update_success'))->success();
                //Buscar ruta desde config
                //return redirect()->route('simplecms.publicaciones.index');
            }

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.store_success'))->success();
            return redirect()->route('simplecms.publicaciones.index');

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();

            return redirect()->back()
                ->withInput();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $publicacion = $this->simpleCMSProvider->findPublicacion($id);
        $categorias = $this->simpleCMSProvider->listarCategoriasPublicacion();

        return view('Lebenlabs/SimpleCMS::Publicaciones.edit', compact('publicacion', 'categorias'));
    }

    /**
     * @param $id
     * @param UpdatePublicacionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function update($id, UpdatePublicacionRequest $request)
    {
        // Si se esta almacenando como borrador este parametro viene en 1
        $saveAsDraft = boolval($request->get('save_as_draft', 0));

        $publicacion = $this->simpleCMSProvider->findPublicacion($id);
        $categoria = $this->simpleCMSProvider->findCategoria($request->get('categoria'));

        $publicacion->setTitulo($request->get('titulo'))
            ->setExtracto($request->get('extracto'))
            ->setCuerpo($request->get('cuerpo'))
            ->setFechaPublicacion($request->get('fecha_publicacion'))
            ->setDestacada(boolval($request->get('destacada', false)))
            ->setPrivada(boolval($request->get('privada', false)))
            ->setPublicada(boolval($request->get('publicada', false)));

        // En caso de que sea borrador quitamos la marca de publicada
        if ($saveAsDraft) {
            $publicacion->setPublicada(false);
        }

        $publicacion->setCategoria($categoria);

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarPublicacion($publicacion);
            $this->connection->commit();


            if ($saveAsDraft) {
                flash(trans('Lebenlabs/SimpleCMS::publicaciones.draft_update_success'))->success();
                //Buscar ruta desde config
                //return redirect()->route('simplecms.publicaciones.index');
            }

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.update_success'))->success();
            return redirect()->route('simplecms.publicaciones.index');

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();

            $categorias = $this->simpleCMSProvider->listarCategoriasPublicacion();

            return redirect()->back()
                ->withInput();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function destroy($id)
    {
        $publicacion = $this->simpleCMSProvider->findPublicacion($id);

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->eliminarPublicacion($publicacion);
            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.destroy_success'))->success();

        } catch (Exception $ex) {
            $this->connection->rollback();
            flash($ex->getMessage())->error();
        }

        return redirect()->route('simplecms.publicaciones.index');
    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicShow($slug)
    {
        $publicacion    = $this->simpleCMSProvider->findPublicacionBySlug($slug);
        $archivos       = $this->storage->get($publicacion);

        return view('Lebenlabs/SimpleCMS::Publicaciones.public-show', compact('publicacion', 'archivos'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicIndex()
    {
        $publicacionesFrontendLimit =  config('simplecms.publicaciones.frontend.indexLimit', 5);
        $publicaciones = $this->simpleCMSProvider->buscarPublicacionesPublicadas(null, $publicacionesFrontendLimit);
        $categorias = $this->simpleCMSProvider->findAllCategoriasPublicadasIndexed();

        return view('Lebenlabs/SimpleCMS::Publicaciones.public-index', compact('publicaciones', 'categorias'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function publicIndexByCategoriaSlug($slug)
    {
        $categoria = $this->simpleCMSProvider->findCategoriaBySlug($slug);

        if (!$categoria) {
            abort(404);
        }

        $publicacionesFrontendLimit =  config('simplecms.publicaciones.frontend.indexByCategoryLimit', 5);
        $publicaciones = $this->simpleCMSProvider->buscarPublicacionesByCategoriaSlug($slug, $publicacionesFrontendLimit);
        $categorias = $this->simpleCMSProvider->findAllCategoriasPublicadasIndexed();

        return view('Lebenlabs/SimpleCMS::Publicaciones.public-index-by-categoria-slug', compact('publicaciones', 'categorias', 'categoria'));
    }

}
