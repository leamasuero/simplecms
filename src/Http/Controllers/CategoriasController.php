<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreCategoriaRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdateCategoriaRequest;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\SimpleCMS;

class CategoriasController extends Controller
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
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(EntityManager $em, SimpleCMS $simpleCMSProvider)
    {
        $this->em = $em;

        // Get connection to use transaction
        $this->connection = $this->em->getConnection();

        // Register services
        $this->simpleCMSProvider = $simpleCMSProvider;

        // Register middleware
        $this->middleware('web');
        $this->middleware(CanManagePublicaciones::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $q = $request->get('q', null);
        $categorias = $this->simpleCMSProvider->buscarCategorias($q);
        $categorias->appends('q', $q);

        if ($q) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.search_result', ['total' => $categorias->total()]))->success();
        }

        return view('Lebenlabs/SimpleCMS::Categorias.index', compact('categorias', 'q'));
    }

    /**
     * @param StoreCategoriaRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function ajaxStore(StoreCategoriaRequest $request)
    {
        $categoria = new Categoria();
        $categoria->setNombre($request->get('nombre'))
            ->setPublicada($request->get('publicada'))
            ->setDestacada($request->get('destacada'));

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarCategoria($categoria);
            $this->connection->commit();

            $reponse = [
                'event' => class_basename(Categoria::class) . '/' . __FUNCTION__,
                'clase' => 'alert alert-successful',
                'msg' => trans('Lebenlabs/SimpleCMS::categorias.store_success'),
                'categoria' => $categoria->toArray(),
            ];

            return response()->json($reponse, Response::HTTP_OK);

        } catch (Exception $ex) {

            $this->connection->rollback();

            $reponse = [
                'event' => class_basename(Categoria::class) . '/' . __FUNCTION__,
                'clase' => 'alert alert-error',
                'msg' => $ex->getMessage(),
                'categoria' => $categoria->toArray(),
            ];

            return response()->json($reponse, Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $categoria = $this->simpleCMSProvider->findCategoria($id);
        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            redirect()->back();
        }

        return view('Lebenlabs/SimpleCMS::Categorias.edit', compact('categoria'));
    }

    /**
     * @param $id
     * @param UpdateCategoriaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function update($id, UpdateCategoriaRequest $request)
    {
        $categoria = $this->simpleCMSProvider->findCategoria($id);
        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        $categoria->setNombre($request->get('nombre'))
            ->setPublicada(boolval($request->get('publicada', false)))
            ->setDestacada(boolval($request->get('destacada', false)));

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarCategoria($categoria);
            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::categorias.update_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();

            return redirect()->route('simplecms.categorias.edit', compact('categoria'))
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
        $categoria = $this->simpleCMSProvider->findCategoria($id);
        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        if ($categoria->isProtegida()) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_allowed'))->error();
            return redirect()->back();
        }

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->eliminarCategoria($categoria);
            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::categorias.destroy_success'))->success();

        } catch (Exception $ex) {
            $this->connection->rollback();
            flash($ex->getMessage())->error();
        }

        return redirect()->route('simplecms.categorias.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $categoria = new Categoria;

        return view('Lebenlabs/SimpleCMS::Categorias.create', compact('categoria'));
    }

    /**
     * @param StoreCategoriaRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function store(StoreCategoriaRequest $request)
    {
        $categoria = new Categoria();

        $categoria->setNombre($request->get('nombre'))
            ->setDestacada(boolval($request->get('destacada', false)))
            ->setPublicada(boolval($request->get('publicada', false)));

        try {

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarCategoria($categoria);
            $this->connection->commit();

            flash(trans('Lebenlabs/SimpleCMS::categorias.store_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();

            return redirect()->route('simplecms.categorias.create', compact('categoria'))
                ->withInput();
        }

    }
}
