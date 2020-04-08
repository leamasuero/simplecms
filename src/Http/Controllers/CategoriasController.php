<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreCategoriaRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdateCategoriaRequest;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Repositories\CategoriaRepo;
use Lebenlabs\SimpleCMS\Services\CategoriasService;
use Lebenlabs\SimpleCMS\SimpleCMS;
use Pagerfanta\View\TwitterBootstrap4View;

class CategoriasController extends Controller
{

    /**
     * @var CategoriasService
     */
    private $categoriasService;

    /**
     * @var SimpleCMS
     */
    private $simpleCMSProvider;

    public function __construct(SimpleCMS $simpleCMSProvider)
    {
        $this->simpleCMSProvider = $simpleCMSProvider;

        $this->categoriasService = $simpleCMSProvider->getCategoriasService();

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
        $paginator = $this->categoriasService->buscar($q)
//            ->setMaxPerPage($request->get('per_page', 10))
            ->setCurrentPage($request->get('page', 1));

        if ($q) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.search_result', ['total' => $paginator->count()]))->success();
        }

        $categorias = $paginator->getCurrentPageResults();

        $routeGenerator = function ($page) use ($request) {
            return route('simplecms.categorias.index', [
                'page' => $page,
                'q' => $request->get('q')]);
        };

        $paginatorView = (new TwitterBootstrap4View())->render($paginator, $routeGenerator, ['proximity' => 3]);

        return view('Lebenlabs/SimpleCMS::Categorias.index', compact('categorias', 'q', 'paginatorView'));
    }

    /**
     * @param StoreCategoriaRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function ajaxStore(StoreCategoriaRequest $request)
    {
        $categoria = new Categoria($request->get('nombre'));
        $categoria
            ->setPublicada($request->get('publicada'))
            ->setDestacada($request->get('destacada'));

        try {

            $this->categoriasService->guardar($categoria);

            $reponse = [
                'event' => class_basename(Categoria::class) . '/' . __FUNCTION__,
                'clase' => 'alert alert-successful',
                'msg' => trans('Lebenlabs/SimpleCMS::categorias.store_success'),
                'categoria' => $categoria->toArray(),
            ];

            return response()->json($reponse, Response::HTTP_OK);

        } catch (Exception $ex) {

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
        $categoria = $this->categoriasService->find($id);

        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
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
        $categoria = $this->categoriasService->find($id);

        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        $categoria
            ->setNombre($request->get('nombre'))
            ->setPublicada((bool)$request->get('publicada'))
            ->setDestacada((bool)$request->get('destacada'));

        try {

            $this->categoriasService->guardar($categoria);

            flash(trans('Lebenlabs/SimpleCMS::categorias.update_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (Exception $e) {

            flash($e->getMessage())->error();

            return redirect()
                ->back()
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
        $categoria = $this->categoriasService->find($id);

        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        if ($categoria->isProtegida()) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_allowed'))->error();
            return redirect()->back();
        }

        try {

            $this->categoriasService->eliminar($categoria);
            flash(trans('Lebenlabs/SimpleCMS::categorias.destroy_success'))->success();

        } catch (Exception $ex) {
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
        $categoria = new Categoria($request->get('nombre'));

        $categoria
            ->setDestacada((bool)$request->get('destacada', false))
            ->setPublicada((bool)$request->get('publicada', false));

        try {

            $this->categoriasService->guardar($categoria);

            flash(trans('Lebenlabs/SimpleCMS::categorias.store_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (Exception $e) {

            flash($e->getMessage())->error();
            return redirect()->back()->withInput();
        }

    }
}
