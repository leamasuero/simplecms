<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Repositories\PublicacionRepo;
use Lebenlabs\SimpleCMS\Repositories\PublicacionRepository;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;

class PublicacionesService
{

    /**
     * @var PublicacionRepo
     */
    private $publicacionRepo;

    /**
     * @var SimpleStorageService
     */
    private $simpleStorageService;

    public function __construct(PublicacionRepo $publicacionRepo, SimpleStorageService $simpletStorageService)
    {
        $this->publicacionRepo = $publicacionRepo;
        $this->simpleStorageService = $simpletStorageService;
    }

    public function buscarPublicadas(?string $q)
    {
        return $this->publicacionRepo->buscar($q, ['publicada' => 1]);
    }

    public function buscar(?string $q, array $criteria = [])
    {
        return $this->publicacionRepo->buscar($q, $criteria);
    }

    public function buscarPrivadas(?string $q)
    {
        return $this->publicacionRepo->buscar($q, ['privada' => 1]);
    }

    public function buscarDestacadas(?string $q, array $criteria = ['destacada' => 1, 'privada' => 0, 'publicada' => 1]): array
    {
        return $this->publicacionRepo->buscar($q, $criteria);
    }

    public function find($id): ?Publicacion
    {
        return $this->publicacionRepo->find($id);
    }

    public function guardar(Publicacion $publicacion)
    {
        if ($publicacion->getId()) {
            return $this->publicacionRepo->update($publicacion);
        }

        return $this->publicacionRepo->insert($publicacion);
    }

    /**
     * Elimina la publicación pasada como parametro desde la base de datos
     *
     * @param Publicacion $publicacion
     * @return type
     */
    public function eliminar(Publicacion $publicacion)
    {
//        // Obtenemos la imagen asociada a la publicacion
//        $imagen = $publicacion->getImagen();
//        if ($imagen) {
//            // Eliminamos la imagen
//            $this->eliminarImagen($imagen);
//        }

        // Obtenemos los archivos asociados a la publicacion
        $archivos = $this->simpleStorageService->get($publicacion);

        foreach ($archivos as $archivo) {
            $this->simpleStorageService->remove($archivo->getId());
        }

        return $this->publicacionRepo->delete($publicacion);
    }

    /**
     * Retorna la publicación con el Slug pasado como parametro
     *
     * @param string $slug
     * @return Publicacion
     */
    public function findPublicacionBySlug(string $slug): ?Publicacion
    {
        return $this->publicacionRepo->findOneBySlug($slug);
    }

    public function buscarPublicadasByCategoriaSlug($slug)
    {
        return $this->publicacionRepo->buscarPublicadasByCategoriaSlug($slug);
    }
}
