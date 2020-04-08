<?php

namespace Lebenlabs\SimpleCMS\Services;

use Doctrine\ORM\EntityManagerInterface;

use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Repositories\CategoriaRepo;
use Lebenlabs\SimpleCMS\Repositories\CategoriaRepository;

class CategoriasService
{

    /**
     * @var CategoriaRepo
     */
    private $categoriaRepo;

    public function __construct(CategoriaRepo $categoriaRepo)
    {
        $this->categoriaRepo = $categoriaRepo;
    }

    public function lists()
    {
        return $this->categoriaRepo->lists();
    }

    /**
     * @param int $id
     * @return Categoria
     */
    public function find(int $id): ?Categoria
    {
        return $this->categoriaRepo->find($id);
    }

    /**
     * @param $q
     * @param int $perPage
     * @return \Pagerfanta\Pagerfanta
     */
    public function buscar(?string $q)
    {
        return $this->categoriaRepo->buscar($q);
    }

    public function guardar(Categoria $categoria)
    {
        if ($categoria->getId()) {
            return $this->categoriaRepo->update($categoria);
        }

        return $this->categoriaRepo->insert($categoria);
    }

    /**
     * Elimina la categoría pasada como parametro desde la base de datos
     *
     * @param Categoria $categoria
     * @return type
     */
    public function eliminar(Categoria $categoria)
    {
        $this->categoriaRepo->delete($categoria);
    }

    /**
     * Retorna la categoría con el Slug pasado como parametro
     *
     * @param string $slug
     * @return type
     */
    public function findOneBySlug(string $slug): ?Categoria
    {
        return $this->categoriaRepo->findOneBySlug($slug);
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
