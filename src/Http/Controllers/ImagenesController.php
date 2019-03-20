<?php

namespace Lebenlabs\SimpleCMS\Http\Controllers;

use Doctrine\ORM\EntityManager;
use Exception;
use Lebenlabs\SimpleCMS\Http\Middleware\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Requests\StoreImagenRequest;
use Lebenlabs\SimpleCMS\SimpleCMS;
use Storage;

class ImagenesController extends Controller
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

        //Register services
        $this->simpleCMSProvider = $simpleCMSProvider;

        // Register middleware
        $this->middleware('web');
        $this->middleware(CanManagePublicaciones::class);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id)
    {
        $publicacion = $this->simpleCMSProvider->findPublicacion($id);

        return view('Lebenlabs/SimpleCMS::Imagenes.create', compact('publicacion'));
    }

    /**
     * @param $id
     * @param StoreImagenRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function store($id, StoreImagenRequest $request)
    {

        try {
            $publicacion = $this->simpleCMSProvider->findPublicacion($id);
            
            $this->connection->beginTransaction();
            $this->simpleCMSProvider->guardarImagen($request, $publicacion);
            $this->simpleCMSProvider->guardarPublicacion($publicacion);
            $this->connection->commit();

            flash(trans('lebenlabs_simplecms.imagenes.store_success'))->success();

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();
        }

        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function destroy($id)
    {
        try {
            $imagen = $this->simpleCMSProvider->findImagen($id);

            if (!$imagen) {
                flash(trans('lebenlabs_simplecms.imagenes.not_found'))->error();
                return back();
            }

            $this->connection->beginTransaction();
            $this->simpleCMSProvider->eliminarImagen($imagen);
            $this->connection->commit();

            flash(trans('lebenlabs_simplecms.imagenes.destroy_success'))->success();

        } catch (Exception $ex) {

            $this->connection->rollback();
            flash($ex->getMessage())->error();
        }

        return back();
    }
}
