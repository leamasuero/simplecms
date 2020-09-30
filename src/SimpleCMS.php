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
     * SimpleCMS constructor.
     *
     * @param EntityManagerInterface $em
     * @param Repository $cache
     * @param ImageManager $imageManager
     * @param SimpleStorageService $simpleStorageService
     */
    public function __construct(EntityManagerInterface $em, Repository $cache, ImageManager $imageManager, SimpleStorageService $simpleStorageService)
    {
        $this->em                   = $em;
        $this->cache                = $cache;
        $this->menuService          = new MenuService($em);
        $this->menuItemsService     = new MenuItemsService($em);
        $this->publicacionesService = new PublicacionesService($em);
        $this->categoriasService    = new CategoriasService($em);
        $this->imagenesService      = new ImagenesService($em, $imageManager);
        $this->simpleStorageService = $simpleStorageService;
    }

    /*********************************************************
     * Menu Methods
     ********************************************************/

    /**
     * Obtiene todos los Menus que existen
     *
     * @return array
     */
    public function findAllMenus()
    {
        return $this->menuService->findAllMenus();
    }

    /**
     * Obtiene el Menu por el nombre dado
     *
     * @param string $nombre
     * @return mixed
     */
    public function findMenuByNombre($nombre)
    {
        return $this->menuService->findMenuByNombre($nombre);
    }

    /**
     * Obtiene el Menu por el id dado
     *
     * @param int $id
     * @return mixed
     */
    public function findMenuById(int $id)
    {
        return $this->menuService->findMenuById($id);
    }

    /*********************************************************
     * Menu Item Methods
     ********************************************************/

    /**
     * Retorna todos los menu items de nivel 0 ordenados
     * por el orden
     *
     * @return array
     */
    public function findAllRootMenuItems()
    {
        return $this->menuItemsService->findAllRootMenuItems();
    }


    /**
     * Obtiene el Menu Item por el id dado
     *
     * @param int $id
     * @return mixed
     */
    public function findMenuItemById(int $id)
    {
        return $this->menuItemsService->findMenuItemById($id);
    }

    /**
     * Guarda en la base de datos el MenuItem pasado como parametro
     *
     * @param MenuItem $menuItem
     * @return MenuItem
     */
    public function guardarMenuItem(MenuItem $menuItem)
    {
        return $this->menuItemsService->guardarMenuItem($menuItem);
    }

    /**
     * Elimina el Menu Item pasado como parametro junto con todos sus
     * menu item hijos en caso de existir
     *
     * @param MenuItem $menuItem
     * @return void
     */
    public function eliminarMenuItem(MenuItem $menuItem)
    {
        $this->menuItemsService->eliminarMenuItem($menuItem);
    }

    /*********************************************************
     * Publicaciones Methods
     ********************************************************/

    /**
     * @param null $q
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscarPublicacionesPublicadas($q = null, $perPage = 20)
    {
        return $this->publicacionesService->buscarPublicacionesPublicadas($q, $perPage);
    }

    /**
     * @param null $q
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscarPublicaciones($q = null, $perPage = 20)
    {
        return $this->publicacionesService->buscarPublicaciones($q, $perPage);
    }

    /**
     * @param null $q
     * @param bool|null $privada
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscarPublicacionesByPrivada($q = null, $privada, $perPage = 20)
    {
        return $this->publicacionesService->buscarPublicacionesByPrivada($q, $privada, $perPage);
    }

    /**
     * @return array
     */
    public function findAllPublicaciones()
    {
        return $this->publicacionesService->findAllPublicaciones();
    }

    /**
     * Retorna la lista de publicaciones destacadas segun el parametro
     * de páginado pasado o 5 por defecto
     *
     * @param int $perPage
     * @return type
     */
    public function findAllPublicacionesDestacadas($perPage = 5)
    {
        return $this->publicacionesService->findAllPublicacionesDestacadas($perPage);
    }


    /**
     * @param int $count
     * @return array
     */
    public function getPublicacionesDestacadas($count = 5)
    {
        return $this->publicacionesService->getPublicacionesDestacadas($count);
    }

    /**
     * @param int $id
     * @return Publicacion
     */
    public function findPublicacion($id)
    {
        return $this->publicacionesService->findPublicacion($id);
    }

    /**
     * @param Publicacion $publicacion
     */
    public function guardarPublicacion(Publicacion $publicacion)
    {
        return $this->publicacionesService->guardarPublicacion($publicacion);
    }

    /**
     * Elimina la publicación pasada como parametro desde la base de datos
     *
     * @param Publicacion $publicacion
     * @return Services\type
     */
    public function eliminarPublicacion(Publicacion $publicacion)
    {
        // Obtenemos la imagen asociada a la publicacion
        $imagen = $publicacion->getImagen();
        if ($imagen) {
            // Eliminamos la imagen
            $this->eliminarImagen($imagen);
        }

        // Obtenemos los archivos asociados a la publicacion
        $archivos = $this->simpleStorageService->get($publicacion);

        foreach ($archivos as $archivo) {
            // Eliminamos cada uno de los archivos
            $this->simpleStorageService->remove($archivo->getId());
        }

        $this->publicacionesService->eliminarPublicacion($publicacion);
    }

    /**
     * Retorna la publicación con el Slug pasado como parametro
     *
     * @param string $slug
     * @return Services\type
     */
    public function findPublicacionBySlug($slug)
    {
        return $this->publicacionesService->findPublicacionBySlug($slug);
    }

    /**
     * Retorna true si la $publicacion pasada como parametro es visible para
     * el $user
     *
     * @param Publicacion $publicacion
     * @return boolean
     */
    public function publicacionIsVisibleForUser(Publicacion $publicacion)
    {
        return $this->publicacionesService->publicacionIsVisibleForUser($publicacion);
    }

    /**
     * @param string $slug
     * @param array $criteria
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscarPublicacionesByCategoriaSlug($slug, $criteria = [], $perPage = 20)
    {
        return $this->publicacionesService->buscarPublicacionesByCategoriaSlug($slug, $criteria, $perPage);
    }


    /*********************************************************
     * Categorias Methods
     ********************************************************/

    /**
     * @return array
     */
    public function listarCategoriasPublicacion()
    {
        return $this->categoriasService->listarCategoriasPublicacion();
    }

    /**
     * @return array
     */
    public function findAllCategoriasPublicadasIndexed()
    {
        return $this->categoriasService->findAllPublicadasIndexed();
    }

    /**
     * @param int $id
     * @return Categoria
     */
    public function findCategoria($id)
    {
        if ($id == null) {
            return null;
        }

        return $this->categoriasService->findCategoria($id);
    }

    /**
     * @param $q
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function buscarCategorias($q, $perPage = 20)
    {
        return $this->categoriasService->buscarCategorias($q, $perPage);
    }

    /**
     * @param Categoria $categoria
     */
    public function guardarCategoria(Categoria $categoria)
    {
        return $this->categoriasService->guardarCategoria($categoria);
    }

    /**
     * Elimina la categoría pasada como parametro desde la base de datos
     *
     * @param Categoria $categoria
     * @return void
     */
    public function eliminarCategoria(Categoria $categoria)
    {
        $this->categoriasService->eliminarCategoria($categoria);
    }

    /**
     * Retorna la categoría con el Slug pasado como parametro
     *
     * @param string $slug
     * @return Services\type
     */
    public function findCategoriaBySlug($slug)
    {
        return $this->categoriasService->findCategoriaBySlug($slug);
    }

    /**
     * Retorna el listado de categorías con la cantidad de Publicaciones
     * visibles asociadas a cada una de estas
     *
     * @return mixed
     */
    public function findCategoriaPublicacionesCount()
    {
        return $this->categoriasService->findCategoriaPublicacionesCount();
    }

    /*********************************************************
     * Imagenes Methods
     ********************************************************/

    /**
     * @param int $id
     * @return Imagen
     */
    public function findImagen($id)
    {
        return $this->imagenesService->findImagen($id);
    }

    /**
     * Elimina la imagen pasada como parametro desde la base de datos
     *
     * @param Imagen $imagen
     * @return void
     */
    public function eliminarImagen(Imagen $imagen)
    {
        return $this->imagenesService->eliminarImagen($imagen);
    }

    /**
     * @param StoreImagenRequest $request
     * @param Publicacion $publicacion
     * @return mixed
     */
    public function guardarImagen(StoreImagenRequest $request, Publicacion $publicacion)
    {
        return $this->imagenesService->guardarImagen($request, $publicacion);
    }
}