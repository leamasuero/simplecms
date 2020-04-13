<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Http\Request;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreArchivosRequest;
use Lebenlabs\SimpleCMS\SimpleCMS;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class ArchivosController extends Controller
{

    /**
     * @var SimpleStorageService
     */
    private $storageService;

    /**
     * @var SimpleCMS
     */
    private $simpleCmsService;

    public function __construct(SimpleCMS $simpleCMSService, SimpleStorageService $storageService)
    {
        $this->storageService = $storageService;

        $this->simpleCmsService = $simpleCMSService;

        // Register middleware
        $this->middleware('web');
        $this->middleware(CanManagePublicaciones::class, ['except' => ['show']]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $entidad = $this->simpleCmsService
            ->getService($request->get('entidad'))
            ->find($request->get('entidad_id'));

        if (!$entidad) {
            flash(trans('Lebenlabs/SimpleCMS::archivos.entidad_not_found'))->error();
            return redirect()->route('simplecms.publicaciones.index');
        }

        $archivos = $this->storageService->get($entidad);

        return view('Lebenlabs/SimpleCMS::Archivos.create', compact('entidad', 'archivos'));
    }

    /**
     * @param StoreArchivosRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function store(StoreArchivosRequest $request)
    {
        $entidad = $this->simpleCmsService
            ->getService($request->get('entidad'))
            ->find($request->get('entidad_id'));

        if (!$entidad) {
            flash(trans('Lebenlabs/SimpleCMS::archivos.entidad_not_found'))->error();
            return redirect()->route('simplecms.publicaciones.index');
        }

        try {

            foreach ($request->file('archivos') as $archivo) {
                $this->storageService->put($entidad, $archivo);
            }

            flash(trans('Lebenlabs/SimpleCMS::archivos.store_success'))->success();
            return redirect()->route('simplecms.archivos.create', ['entidad' => $request->get('entidad'), 'entidad_id' => $request->get('entidad_id')]);

        } catch (Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back();
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->storageService->remove($id);

            flash(trans('Lebenlabs/SimpleCMS::archivos.destroy_success'))->success();
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->back();
    }

}
