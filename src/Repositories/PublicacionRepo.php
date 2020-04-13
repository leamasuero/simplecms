<?php

namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleCMS\Adapters\SimpleCmsAdapter;
use Lebenlabs\SimpleCMS\Transformers\PublicacionTransformer;
use Lebenlabs\SimpleCMS\Models\Publicacion;
use Pagerfanta\Pagerfanta;

class PublicacionRepo
{
    /**
     * @var Connectionn
     */
    private $connection;

    /**
     * @var PublicacionTransformer
     */
    private $publicacionTransformer;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->publicacionTransformer = new PublicacionTransformer();
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
            ->from('simplecms_publicaciones', 'Publicacion')
            ->leftJoin('Publicacion', 'simplecms_categorias', 'Categoria', 'Publicacion.categoria_id = Categoria.id')
            ->orderBy('Publicacion.fecha_publicacion', 'desc');

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

        return new Pagerfanta(new SimpleCmsAdapter($qb, $countQueryBuilderModifier, new PublicacionTransformer()));
    }


    public function insert(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert('simplecms_publicaciones')
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
                    'protegida' => ':protegida',
                    'notificable' => ':notificable',
                    'notificada_at' => ':notificada_at',
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
                'protegida' => (int)$publicacion->isProtegida(),
                'notificable' => (int)$publicacion->isNotificable(),
                'notificada_at' => $publicacion->getNotificadaAt(),
                'categoria_id' => $publicacion->getCategoria()->getId(),
                'created_at' => $publicacion->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }


    public function update(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update('simplecms_publicaciones')
            ->set('titulo', ':titulo')
            ->set('slug', ':slug')
            ->set('extracto', ':extracto')
            ->set('cuerpo', ':cuerpo')
            ->set('fecha_publicacion', ':fecha_publicacion')
            ->set('destacada', ':destacada')
            ->set('privada', ':privada')
            ->set('publicada', ':publicada')
            ->set('notificable', ':notificable')
            ->set('notificada_at', ':notificada_at')
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
                'notificable' => (int)$publicacion->isNotificable(),
                'notificada_at' => $publicacion->getNotificadaAt() ? $publicacion->getNotificadaAt()->format('Y-m-d H:i:s') : null,
                'categoria_id' => $publicacion->getCategoria()->getId(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }


    public function delete(Publicacion $publicacion)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete('simplecms_publicaciones')
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
            ->from('simplecms_publicaciones', 'p')
            ->leftJoin('p', 'simplecms_categorias', 'c', 'p.categoria_id = c.id')
            ->where('p.id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return $this->publicacionTransformer->transform($st->fetch());
    }

    public function findOneBySlug(string $slug): ?Publicacion
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('simplecms_publicaciones')
            ->where('slug = :slug')
            ->setParameter(':slug', $slug)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return $this->publicacionTransformer->transform($st->fetch());
    }


    public function findPublicadasNotificablesPendientesByFechaPublicacion(\DateTimeImmutable $fechaPublicacion): iterable
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('Publicacion.*', 'Categoria.id as categoria_id', 'Categoria.nombre as categoria_nombre')
            ->from('simplecms_publicaciones', 'Publicacion')
            ->leftJoin('Publicacion', 'simplecms_categorias', 'Categoria', 'Publicacion.categoria_id = Categoria.id')
            ->where('Publicacion.fecha_publicacion BETWEEN :fecha_publicacion_starts AND :fecha_publicacion_ends')
            ->andWhere('Publicacion.publicada = 1')
            ->andWhere('Publicacion.notificable = 1')
            ->andWhere('Publicacion.notificada_at is NULL')
            ->setParameter(':fecha_publicacion_starts', $fechaPublicacion->format('Y-m-d 00:00:00'))
            ->setParameter(':fecha_publicacion_ends', $fechaPublicacion->format('Y-m-d 23:59:59'));

        $st = $qb->execute();

        return $this->publicacionTransformer->transformCollection($st->fetchAll());
    }

    public function buscarPublicadasByCategoriaSlug(string $slug): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('Publicacion.*', 'Categoria.id as categoria_id', 'Categoria.nombre as categoria_nombre')
            ->from('simplecms_publicaciones', 'Publicacion')
            ->leftJoin('Publicacion', 'simplecms_categorias', 'Categoria', 'Publicacion.categoria_id = Categoria.id')
            ->where('Categoria.slug = :slug')
            ->andWhere('Publicacion.publicada = 1')
            ->setParameter(':slug', $slug);

        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT Publicacion.id) AS total_results')
                ->setMaxResults(1);
        };

        return new Pagerfanta(new SimpleCmsAdapter($qb, $countQueryBuilderModifier, new PublicacionTransformer()));

    }


}