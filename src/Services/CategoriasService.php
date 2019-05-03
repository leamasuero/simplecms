<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;

use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Repositories\CategoriaRepository;

class CategoriasService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var CategoriaRepository
     */
    private $categoriaRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->categoriaRepository = $this->em->getRepository(Categoria::class);
    }

    public function listarCategoriasPublicacion()
    {
        return $this->categoriaRepository->lists();
    }

    public function findAllPublicadasIndexed()
    {
        return $this->categoriaRepository->findAllPublicadasIndexed();
    }

    /**
     * @param int $id
     * @return Categoria
     */
    public function findCategoria($id)
    {
        return $this->categoriaRepository->find($id);
    }

    public function buscarCategorias($q, $perPage = 20)
    {
        return $this->categoriaRepository->buscar($q, $perPage);
    }

    public function guardarCategoria(Categoria $categoria)
    {
        $this->em->persist($categoria);
        $this->em->flush();
    }

    /**
     * Elimina la categoría pasada como parametro desde la base de datos
     *
     * @param Categoria $categoria
     * @return type
     */
    public function eliminarCategoria(Categoria $categoria)
    {
        $this->em->remove($categoria);
        $this->em->flush();
    }

    /**
     * Retorna la categoría con el Slug pasado como parametro
     *
     * @param string $slug
     * @return type
     */
    public function findCategoriaBySlug($slug)
    {
        return $this->categoriaRepository->findOneBySlug($slug);
    }

    /**
     * Retorna el listado de categorías con la cantidad de Publicaciones
     * visibles asociadas a cada una de estas
     *
     * @return mixed
     */
    public function findCategoriaPublicacionesCount()
    {
        return $this->categoriaRepository->findCategoriaPublicacionesCount();
    }
}
