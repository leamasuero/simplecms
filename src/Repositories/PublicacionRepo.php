<?php

namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleCMS\Factories\PublicacionFactory;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Pagerfanta\Adapter\DoctrineDbalSingleTableAdapter;
use Pagerfanta\Pagerfanta;

class PublicacionRepo
{
    /**
     * @var Connectionn
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string $q
     * @param array $criteria
     * @return Pagerfanta
     */
    public function buscar(?string $q = null, array $criteria = [])
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('Publicacion.*', 'Categoria.id as categoria_id', 'Categoria.nombre as categoria_nombre')
            ->from('lebenlabs_simplecms_publicaciones', 'Publicacion')
            ->leftJoin('Publicacion', 'lebenlabs_simplecms_categorias', 'Categoria', 'Publicacion.categoria_id = Categoria.id')
            ->orderBy('Publicacion.id', 'desc');

        if ($q) {
            $qb
                ->andWhere('Publicacion.titulo LIKE :q')
                ->orWhere('Publicacion.cuerpo LIKE :q')
                ->orWhere('Categoria.nombre LIKE :q')
                ->setParameter('q', "%{$q}%");
        }

        $criteriaFiltrada = array_filter($criteria);

        foreach ($criteriaFiltrada as $k => $value) {
            $qb->andWhere("Publicacion.{$k} = :{$k}")
                ->setParameter($k, $value);
        }

        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT Publicacion.id) AS total_results')
                ->setMaxResults(1);
        };

        return new Pagerfanta(new DoctrineDbalAdapter($qb, $countQueryBuilderModifier));
    }


    public function insert(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert('lebenlabs_simplecms_publicaciones')
            ->values(
                [
                    'titulo' => ':titulo',
                    'slug' => ':slug',
                    'extracto' => ':extracto',
                    'cuerpo' => ':cuerpo',
                    'fecha_publicacion' => ':fecha_publicacion',
                    'destacada' => ':destacada',
                    'privada' => ':privada',
                    'publicada' => ':publicada',
                    'categoria_id' => ':categoria_id',
                    'created_at' => ':created_at',
                    'updated_at' => ':updated_at',
                ]
            )
            ->setParameters([
                'titulo' => $publicacion->getTitulo(),
                'slug' => $publicacion->getSlug(),
                'extracto' => $publicacion->getExtracto(),
                'cuerpo' => $publicacion->getCuerpo(),
                'fecha_publicacion' => $publicacion->getFechaPublicacion()->format('Y-m-d'),
                'destacada' => (int)$publicacion->isDestacada(),
                'privada' => (int)$publicacion->isPrivada(),
                'publicada' => (int)$publicacion->isPublicada(),
                'categoria_id' => $publicacion->getCategoria()->getId(),
                'created_at' => $publicacion->getCreatedAt()->format('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }


    public function update(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update('lebenlabs_simplecms_publicaciones')
            ->set('titulo', ':titulo')
            ->set('slug', ':slug')
            ->set('extracto', ':extracto')
            ->set('cuerpo', ':cuerpo')
            ->set('fecha_publicacion', ':fecha_publicacion')
            ->set('destacada', ':destacada')
            ->set('privada', ':privada')
            ->set('publicada', ':publicada')
            ->set('categoria_id', ':categoria_id')
            ->set('updated_at', ':updated_at')
            ->where('id = :id')
            ->setParameters([
                'id' => $publicacion->getId(),
                'titulo' => $publicacion->getTitulo(),
                'slug' => $publicacion->getSlug(),
                'extracto' => $publicacion->getExtracto(),
                'cuerpo' => $publicacion->getCuerpo(),
                'fecha_publicacion' => $publicacion->getFechaPublicacion()->format('Y-m-d'),
                'destacada' => (int)$publicacion->isDestacada(),
                'privada' => (int)$publicacion->isPrivada(),
                'publicada' => (int)$publicacion->isPublicada(),
                'categoria_id' => $publicacion->getCategoria()->getId(),
                'created_at' => $publicacion->getCreatedAt()->format('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }


    public function delete(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete('lebenlabs_simplecms_publicaciones')
            ->where('id = :id')
            ->setParameters([
                'id' => $publicacion->getId(),
            ]);

        return $qb->execute();
    }


    public function find(int $id): ?Publicacion
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.*', 'c.id as categoria_id', 'c.nombre as categoria_nombre')
            ->from('lebenlabs_simplecms_publicaciones', 'p')
            ->leftJoin('p', 'lebenlabs_simplecms_categorias', 'c', 'p.categoria_id = c.id')
            ->where('p.id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return PublicacionFactory::create($st->fetch());
    }

    public function findOneBySlug(string $slug): ?Publicacion
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('lebenlabs_simplecms_publicaciones')
            ->where('slug = :slug')
            ->setParameter(':slug', $slug)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return PublicacionFactory::create($st->fetch());
    }

    public function buscarPublicadasByCategoriaSlug(string $slug): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('Publicacion.*', 'Categoria.id as categoria_id', 'Categoria.nombre as categoria_nombre')
            ->from('lebenlabs_simplecms_publicaciones', 'Publicacion')
            ->leftJoin('Publicacion', 'lebenlabs_simplecms_categorias', 'Categoria', 'Publicacion.categoria_id = Categoria.id')
            ->where('Categoria.slug = :slug')
            ->andWhere('Publicacion.publicada = 1')
            ->setParameter(':slug', $slug);

        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT Publicacion.id) AS total_results')
                ->setMaxResults(1);
        };

        return new Pagerfanta(new DoctrineDbalAdapter($qb, $countQueryBuilderModifier));
    }


}