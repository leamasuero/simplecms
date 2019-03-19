<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Doctrine\ORM\EntityManager;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreArchivosRequest;
use SimpleStorage\Services\SimpleStorageService;

class ArchivosController extends Controller
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

    public function __construct(EntityManager $em, SimpleStorageService $storage)
    {
        parent::__construct();
        $this->em = $em;

        // Get connection to use transaction
        $this->connection = $this->em->getConnection();

        $this->storage = $storage;

        // Register middleware
        $this->middleware('web');
        $this->middleware(CanManagePublicaciones::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $entidad = $this->em
            ->getRepository($request->get('entidad'))
            ->find($request->get('entidad_id'));

        if (!$entidad) {
            flash(trans('lebenlabs_simplecms.archivos.entidad_not_found'))->error();
            return redirect()->route('simplecms.publicaciones.index');
        }

        $archivos = $this->storage->get($entidad);

        return view('Lebenlabs/SimpleCMS::Archivos.create', compact('entidad', 'archivos'));
    }

    /**
     * @param StoreArchivosRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function store(StoreArchivosRequest $request)
    {
        $entidad = $this->em->getRepository($request->get('entidad'))->find($request->get('entidad_id'));

        if (!$entidad) {
            flash(trans('lebenlabs_simplecms.archivos.entidad_not_found'))->error();
            return redirect()->route('simplecms.publicaciones.index');
        }

        try {

            $this->connection->beginTransaction();

            foreach ($request->file('archivos') as $archivo) {
                $this->storage->put($entidad, $archivo);
            }

            $this->connection->commit();

            flash(trans('lebenlabs_simplecms.archivos.store_success'))->success();

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();
        }


        return redirect(route('simplecms.archivos.create', ['entidad' => $request->get('entidad'), 'entidad_id' => $request->get('entidad_id')]));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {

            $this->connection->beginTransaction();
            $this->storage->remove($id);
            $this->connection->commit();

            flash(trans('lebenlabs_simplecms.archivos.destroy_success'))->success();
        } catch (Exception $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\Response
     * @throws \SimpleStorage\Exceptions\NotFoundException
     */
    public function show($id)
    {
        $storageItem = $this->storage->find($id);

        return response()->make(
            $storageItem->getArchivo(), Response::HTTP_OK, [
            'Content-Type' => $this->storage->mimeType($storageItem),
            'Content-Disposition' => "attachment;filename={$storageItem->getOriginalFilename()}"
        ]);
    }
}
