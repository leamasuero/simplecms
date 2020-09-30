<?php

namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\ORM\EntityRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Lebenlabs\SimpleCMS\Models\Publicacion;

class PublicacionRepository extends EntityRepository
{

    use \LaravelDoctrine\ORM\Pagination\Paginatable;

    /**
     *
     * @param string $q
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarPublicadas($q, $perPage = 10)
    {
        return $this->buscar($q, $perPage, true, false);
    }

    /**
     * @param $q
     * @param bool $privada
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarByPrivada($q, $privada, $perPage = 10)
    {
        return $this->buscar($q, $perPage, null, $privada);
    }

    /**
     * @param $q
     * @param int $perPage
     * @param null $publicada
     * @param null $privada
     * @return LengthAwarePaginator
     */
    public function buscar($q, $perPage = 10, $publicada = null, $privada = null)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Publicacion')
            ->from(Publicacion::class, 'Publicacion')
            ->leftJoin('Publicacion.categoria', 'Categoria')
            ->orderBy('Publicacion.id', 'desc');

        if ($q) {
            $qb->andWhere('Publicacion.titulo LIKE :q')
                ->orWhere('Publicacion.cuerpo LIKE :q')
                ->orWhere('Categoria.nombre LIKE :q')
                ->setParameter('q', "%{$q}%");
        }

        if ($publicada) {
            $qb->andWhere('Publicacion.publicada = :publicada')
                ->setParameter('publicada', $publicada);
        }

        if ($privada !== null) {
            $qb->andWhere('Publicacion.privada = :privada')
                ->setParameter('privada', $privada);
        }

        return $this->paginate($qb->getQuery(), $perPage);
    }

    /**
     *
     * @param string $slug
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarPublicacionesByCategoriaSlug($slug, $criteria = [], $perPage = 10)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Publicacion')
            ->from(Publicacion::class, 'Publicacion')
            ->leftJoin('Publicacion.categoria', 'Categoria')
            ->where('Categoria.slug = :slug')
            ->setParameter('slug', $slug)
            ->orderBy('Publicacion.id', 'desc');

        $criteriaFiltrada = array_filter($criteria, function ($value, $key) {
            // filtro todos los criterios que sean validos (esten en el array) y que no sean nulos
            return !is_null($value) && in_array($key, ['privada', 'publicada']);
        }, ARRAY_FILTER_USE_BOTH);


        foreach ($criteriaFiltrada as $k => $value) {
            $qb->andWhere("Publicacion.{$k} = :{$k}")
                ->setParameter($k, $value);
        }

        return $this->paginate($qb->getQuery(), $perPage);
    }

    /**
     * Busca las publicaciones destacadas devolviendo por
     * defecto 5 en caso de que no se esepcifique
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findDestacadas($perPage = 5)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('Publicacion')
            ->from(Publicacion::class, 'Publicacion')
            ->where('Publicacion.destacada = true')
            ->andWhere('Publicacion.publicada = true')
            ->andWhere('Publicacion.privada = 0')
            ->orderBy('Publicacion.id', 'desc');

        return $this->paginate($qb->getQuery(), $perPage);
    }
}
