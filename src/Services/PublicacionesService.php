<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Lebenlabs\SimpleCMS\Repositories\PublicacionRepository;

class PublicacionesService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PublicacionRepository
     */
    private $publicacionRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->publicacionRepository = $this->em->getRepository(Publicacion::class);
    }

    public function buscarPublicacionesPublicadas($q, $perPage = 20)
    {
        return $this->publicacionRepository->buscarPublicadas($q, $perPage);
    }

    public function buscarPublicaciones($q, $perPage = 20)
    {
        return $this->publicacionRepository->buscar($q, $perPage);
    }

    public function findAllPublicaciones()
    {
        return $this->publicacionRepository->findAll();
    }

    public function findAllPublicacionesDestacadas($perPage = 5)
    {
        return $this->publicacionRepository->findDestacadas($perPage);
    }

    public function findPublicacion($id)
    {
        return $this->publicacionRepository->find($id);
    }

    public function guardarPublicacion(Publicacion $publicacion)
    {
        $this->em->persist($publicacion);
        $this->em->flush();
    }

    /**
     * Elimina la publicación pasada como parametro desde la base de datos
     *
     * @param Publicacion $publicacion
     * @return type
     */
    public function eliminarPublicacion(Publicacion $publicacion)
    {
        $this->em->remove($publicacion);
        $this->em->flush();
    }

    /**
     * Retorna la publicación con el Slug pasado como parametro
     *
     * @param string $slug
     * @return type
     */
    public function findPublicacionBySlug($slug)
    {
        return $this->publicacionRepository->findOneBySlug($slug);
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


        return false;
    }

    public function buscarPublicacionesByCategoriaSlug($slug, $perPage = 20)
    {
        return $this->publicacionRepository->buscarPublicacionesByCategoriaSlug($slug, $perPage);
    }


    /**
     * @param int $count
     * @return array
     */
    public function getPublicacionesDestacadas($count = 5)
    {
        return $this->publicacionRepository->findBy(['destacada' => 1, 'publicada' => 1], ['id' => 'desc'], $count, 0);
    }
}
