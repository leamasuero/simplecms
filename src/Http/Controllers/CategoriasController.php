<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreCategoriaRequest;
use Lebenlabs\SimpleCMS\Http\Requests\UpdateCategoriaRequest;
use Lebenlabs\SimpleCMS\Models\Categoria;
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

    public function edit(int $id)
    {
        $categoria = $this->categoriasService->find($id);

        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        return view('Lebenlabs/SimpleCMS::Categorias.edit', compact('categoria'));
    }

    public function update(int $id, UpdateCategoriaRequest $request)
    {
        $categoria = $this->categoriasService->find($id);

        if (!$categoria) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.not_found'))->error();
            return redirect()->back();
        }

        try {

            $categoria
                ->setNombre($request->get('nombre'))
                ->setPublicada((bool)$request->get('publicada'))
                ->setDestacada((bool)$request->get('destacada'));


            $this->categoriasService->guardar($categoria);

            flash(trans('Lebenlabs/SimpleCMS::categorias.update_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.fail_unique_title_violation'))->error();
            return redirect()->back()->withInput();

        } catch (Exception $e) {

            flash($e->getMessage())->error();

            return redirect()
                ->back()
                ->withInput();
        }
    }

    public function destroy(int $id)
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

    public function create()
    {
        $categoria = new Categoria;

        return view('Lebenlabs/SimpleCMS::Categorias.create', compact('categoria'));
    }

    public function store(StoreCategoriaRequest $request)
    {
        $categoria = new Categoria($request->get('nombre'));

        try {

            $categoria
                ->setDestacada((bool)$request->get('destacada'))
                ->setPublicada((bool)$request->get('publicada'));

            $this->categoriasService->guardar($categoria);

            flash(trans('Lebenlabs/SimpleCMS::categorias.store_success'))->success();
            return redirect()->route('simplecms.categorias.index');

        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            flash(trans('Lebenlabs/SimpleCMS::categorias.fail_unique_title_violation'))->error();
            return redirect()->back()->withInput();

        } catch (Exception $e) {

            flash($e->getMessage())->error();
            return redirect()->back()->withInput();
        }

    }
}
