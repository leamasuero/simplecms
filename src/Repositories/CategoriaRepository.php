<?php

namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Illuminate\Pagination\LengthAwarePaginator;
use Lebenlabs\SimpleCMS\Models\Categoria;

class CategoriaRepository extends EntityRepository
{

    use \LaravelDoctrine\ORM\Pagination\Paginatable;

    public function lists()
    {
        $categorias = $this->_em->createQuery('SELECT c.id,c.nombre FROM Lebenlabs\SimpleCMS\Models\Categoria c')->getResult(Query::HYDRATE_ARRAY);
        return array_pluck(array_values($categorias), 'nombre', 'id');
    }

    /**
     * Devuelve todas las categorias publicadas
     * @return array
     */
    public function findAllPublicadasIndexed()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Categoria')
            ->from(Categoria::class, 'Categoria', 'Categoria.slug')
            ->where('Categoria.publicada = true')
            ->orderBy('Categoria.id', 'desc');

        return $qb->getQuery()->getResult();
    }

    /**
     *
     * @param type $q
     * @param type $perPage
     * @return LengthAwarePaginator
     */
    public function buscar($q, $perPage = 10)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Categoria')
            ->from(Categoria::class, 'Categoria')
            ->orderBy('Categoria.id', 'desc');

        if ($q) {
            $qb->where('Categoria.nombre LIKE :q')
                ->setParameter('q', "%{$q}%");
        }

        return $this->paginate($qb->getQuery(), $perPage);
    }
}
