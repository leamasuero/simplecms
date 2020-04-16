<?php

namespace Lebenlabs\SimpleCMS\Services;

use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Repositories\PublicacionRepo;
use Lebenlabs\SimpleStorage\Services\SimpleStorageService;
use Pagerfanta\PagerfantaInterface;

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

    public function buscarPublicadas(?string $q = null): PagerfantaInterface
    {
        return $this->publicacionRepo->buscar($q, ['publicada' => 1]);
    }

    public function buscar(?string $q = null, array $criteria = []): PagerfantaInterface
    {
        return $this->publicacionRepo->buscar($q, $criteria);
    }

    public function buscarPrivadas(?string $q = null): PagerfantaInterface
    {
        return $this->publicacionRepo->buscar($q, ['privada' => 1]);
    }

    public function buscarPrivadasPublicadas(?string $q = null): PagerfantaInterface
    {
        return $this->publicacionRepo->buscar($q, ['privada' => 1, 'publicada' => 1]);
    }

    public function buscarDestacadas(?string $q = null, array $criteria = ['destacada' => 1, 'privada' => 0, 'publicada' => 1]): PagerfantaInterface
    {
        return $this->publicacionRepo->buscar($q, $criteria);
    }

    public function find(int $id): ?Publicacion
    {
        return $this->publicacionRepo->find($id);
    }

    public function guardar(Publicacion $publicacion): int
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
    public function eliminar(Publicacion $publicacion): int
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

    public function findBySlug(string $slug): ?Publicacion
    {
        return $this->publicacionRepo->findOneBySlug($slug);
    }

    public function buscarPublicadasByCategoriaSlug(string $slug): iterable
    {
        return $this->publicacionRepo->buscarPublicadasByCategoriaSlug($slug);
    }

    public function findPublicadasNotificablesPendientesByFechaPublicacion(\DateTimeImmutable $fechaPublicacion): iterable
    {
        return $this->publicacionRepo->findPublicadasNotificablesPendientesByFechaPublicacion($fechaPublicacion);
    }
}
