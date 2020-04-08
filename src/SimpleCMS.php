<?php

namespace Lebenlabs\SimpleCMS;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Cache\Repository;
use Intervention\Image\ImageManager;
use Lebenlabs\SimpleCMS\Http\Requests\StoreImagenRequest;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Models\Imagen;
use Lebenlabs\SimpleCMS\Models\MenuItem;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Repositories\CategoriaRepo;
use Lebenlabs\SimpleCMS\Repositories\PublicacionRepo;
use Lebenlabs\SimpleCMS\Services\CategoriasService;
use Lebenlabs\SimpleCMS\Services\ImagenesService;
use Lebenlabs\SimpleCMS\Services\MenuItemsService;
use Lebenlabs\SimpleCMS\Services\MenuService;
use Lebenlabs\SimpleCMS\Services\PublicacionesService;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

/**
 * Sirve como proxy Service para llamadas a los demas Service
 *
 * Class SimpleCMS
 * @package Lebenlabs\SimpleCMS
 */
class SimpleCMS
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var MenuService
     */
    private $menuService;

    /**
     * @var MenuItemsService
     */
    private $menuItemsService;

    /**
     * @var PublicacionesService
     */
    private $publicacionesService;

    /**
     * @var CategoriasService
     */
    private $categoriasService;

    /**
     * @var ImagenesService
     */
    private $imagenesService;


    /**
     * @var SimpleStorageService
     */
    private $simpleStorageService;

    /**
     * @var Repository
     */
    private $cache;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * SimpleCMS constructor.
     *
     * @param EntityManagerInterface $em
     * @param Repository $cache
     * @param ImageManager $imageManager
     * @param SimpleStorageService $simpleStorageService
     */
    public function __construct(SimpleStorageService $simpleStorageService, Connection $connection)
    {
        $this->simpleStorageService = $simpleStorageService;
        $this->connection = $connection;
        $this->categoriasService = new CategoriasService(new CategoriaRepo($connection));
        $this->publicacionesService = new PublicacionesService(new PublicacionRepo($connection), $simpleStorageService);
    }

//
//    /**
//     * Retorna true si la $publicacion pasada como parametro es visible para
//     * el $user
//     *
//     * @param Publicacion $publicacion
//     * @return boolean
//     */
//    public function publicacionIsVisibleForUser(Publicacion $publicacion)
//    {
//        return $this->publicacionesService->publicacionIsVisibleForUser($publicacion);
//    }


    /*********************************************************
     * Categorias Methods
     ********************************************************/

//    /**
//     * @return array
//     */
//    public function listarCategoriasPublicacion()
//    {
//        return $this->categoriasService->listarCategoriasPublicacion();
//    }

//    /**
//     * @return array
//     */
//    public function findAllCategoriasPublicadasIndexed()
//    {
//        return $this->categoriasService->findAllPublicadasIndexed();
//    }


//    /**
//     * Retorna el listado de categorías con la cantidad de Publicaciones
//     * visibles asociadas a cada una de estas
//     *
//     * @return mixed
//     */
//    public function findCategoriaPublicacionesCount()
//    {
//        return $this->categoriasService->findCategoriaPublicacionesCount();
//    }

    public function getCategoriasService(): CategoriasService
    {
        return $this->categoriasService;
    }

    public function getPublicacionesService(): PublicacionesService
    {
        return $this->publicacionesService;
    }

    public function getService(string $entidad)
    {
        switch ($entidad) {
            case Publicacion::class:
                return $this->getPublicacionesService();
            case Categoria::class:
                return $this->getCategoriasService();
            default:
                throw new \InvalidArgumentException($entidad);

        }

    }
}