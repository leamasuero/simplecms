<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Middleware\PublicacionExiste;
use Lebenlabs\SimpleCMS\Http\Requests\StorePublicacionRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdatePublicacionRequest;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Services\CategoriasService;
use Lebenlabs\SimpleCMS\Services\PublicacionesService;
use Lebenlabs\SimpleCMS\SimpleCMS;
use Pagerfanta\View\TwitterBootstrap4View;

class PublicacionesController extends Controller
{
    /**
     * @var SimpleCMS
     */
    private $simpleCMSService;

    /**
     * @var PublicacionesService
     */
    private $publicacionesService;

    /**
     * @var CategoriasService
     */
    private $categoriasService;

    public function __construct(SimpleCMS $simpleCMSService)
    {
        // Register services
        $this->simpleCMSService = $simpleCMSService;
        $this->categoriasService = $simpleCMSService->getCategoriasService();
        $this->publicacionesService = $simpleCMSService->getPublicacionesService();

        // Register middleware
        $this->middleware('web');

        $this->middleware(CanManagePublicaciones::class, ['only' => ['edit', 'update', 'create', 'store', 'destroy', 'index']]);
    }

    public function index(Request $request)
    {
        $q = $request->get('q', null);
        $privada = $request->get('privada', null);

        $paginator = $this->publicacionesService->buscar($q, compact('privada'))
//            ->setMaxPerPage($request->get('per_page', 1))
            ->setCurrentPage($request->get('page', 1));

        $publicaciones = $paginator->getCurrentPageResults();

        if ($q) {
            flash(trans('Lebenlabs/SimpleCMS::publicaciones.search_result', ['total' => $paginator->count()]))->success();
        }

        $routeGenerator = function ($page) use ($request) {
            return route('simplecms.publicaciones.index', [
                'page' => $page,
                'q' => $request->get('q'),
                'privada' => $request->get('privada'),
            ]);
        };

        $paginatorView = (new TwitterBootstrap4View())->render($paginator, $routeGenerator, ['proximity' => 3]);
        return view('Lebenlabs/SimpleCMS::Publicaciones.index', compact('publicaciones', 'q', 'paginatorView'));
    }

    public function create()
    {
        $publicacion = new Publicacion;

        $categorias = $this->categoriasService->lists();

        return view('Lebenlabs/SimpleCMS::Publicaciones.create', compact('publicacion', 'categorias'));
    }

    public function store(StorePublicacionRequest $request)
    {
        $publicacion = new Publicacion();

        $categoria = $this->categoriasService->find($request->get('categoria'));

        $publicacion
            ->setTitulo($request->get('titulo'))
            ->setExtracto($request->get('extracto'))
            ->setCuerpo($request->get('cuerpo'))
            ->setFechaPublicacion(\DateTime::createFromFormat('Y-m-d', $request->get('fecha_publicacion')))
            ->setDestacada((bool)$request->get('destacada'))
            ->setPrivada((bool)$request->get('privada'))
            ->setPublicada((bool)$request->get('publicada'))
            ->setNotificable((bool)$request->get('notificable'))
            ->setCategoria($categoria);

        try {

            $this->publicacionesService->guardar($publicacion);

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.store_success'))->success();
            return redirect()->route('simplecms.publicaciones.index');

        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            flash(trans('Lebenlabs/SimpleCMS::publicaciones.fail_unique_title_violation'))->error();
            return redirect()->back()->withInput();

        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back()->withInput();
        }
    }

    public function edit(int $id)
    {
        $publicacion = $this->publicacionesService->find($id);
        $categorias = $this->categoriasService->lists();

        return view('Lebenlabs/SimpleCMS::Publicaciones.edit', compact('publicacion', 'categorias'));
    }

    public function update(int $id, UpdatePublicacionRequest $request)
    {
        $publicacion = $this->publicacionesService->find($id);
        $categoria = $this->categoriasService->find($request->get('categoria'));

        try {

            $publicacion
                ->setTitulo($request->get('titulo'))
                ->setExtracto($request->get('extracto'))
                ->setCuerpo($request->get('cuerpo'))
                ->setFechaPublicacion(\DateTime::createFromFormat('Y-m-d', $request->get('fecha_publicacion')))
                ->setDestacada((bool)$request->get('destacada'))
                ->setPrivada((bool)$request->get('privada'))
                ->setPublicada((bool)$request->get('publicada'))
                ->setCategoria($categoria);

            if (!$publicacion->isNotificada()) {
                $publicacion->setNotificable((bool)$request->get('notificable'));
            }


            $this->publicacionesService->guardar($publicacion);

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.update_success'))->success();
            return redirect()->route('simplecms.publicaciones.index');

        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            flash(trans('Lebenlabs/SimpleCMS::publicaciones.fail_unique_title_violation'))->error();
            return redirect()->back()->withInput();

        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back()->withInput();
        }
    }

    public function destroy(int $id)
    {
        $publicacion = $this->publicacionesService->find($id);

        try {

            $this->publicacionesService->eliminar($publicacion);

            flash(trans('Lebenlabs/SimpleCMS::publicaciones.destroy_success'))->success();
            return redirect()->route('simplecms.publicaciones.index');

        } catch (Exception $ex) {
            flash($ex->getMessage())->error();
            return redirect()->back();
        }

    }
}
