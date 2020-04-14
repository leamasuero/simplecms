<?php


namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleCMS\Adapters\SimpleCmsAdapter;
use Lebenlabs\SimpleCMS\Transformers\CategoriaTransformer;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Pagerfanta\Pagerfanta;

class CategoriaRepo
{
    /**
     * @var Connectionn
     */
    private $connection;

    /**
     * @var CategoriaTransformer
     */
    private $categoriaTransformer;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->categoriaTransformer = new CategoriaTransformer();
    }

    /**
     * @param string $q
     * @param int $perPage
     * @return Pagerfanta
     */
    public function buscar(?string $q): Pagerfanta
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(Categoria::$tabla, 'Categoria')
            ->orderBy('Categoria.id', 'desc');

        if ($q) {
            $qb->where('Categoria.nombre LIKE :q')
                ->setParameter('q', "%{$q}%");
        }

        $countQueryBuilderModifier = function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT Categoria.id) AS total_results')
                ->setMaxResults(1);
        };

        return new Pagerfanta(new SimpleCmsAdapter($qb, $countQueryBuilderModifier, new CategoriaTransformer()));
    }

    public function find(int $id): Categoria
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(Categoria::$tabla)
            ->where('id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return $this->categoriaTransformer->transform($st->fetch());
    }

    public function lists(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('id, nombre')
            ->from(Categoria::$tabla);

        $st = $qb->execute();

        return $st->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function insert(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert(Categoria::$tabla)
            ->values(
                [
                    'nombre' => ':nombre',
                    'slug' => ':slug',
                    'destacada' => ':destacada',
                    'publicada' => ':publicada',
                    'protegida' => ':protegida',
                    'created_at' => ':created_at',
                    'updated_at' => ':updated_at',
                ]
            )
            ->setParameters([
                'nombre' => $categoria->getNombre(),
                'slug' => $categoria->getSlug(),
                'destacada' => (int)$categoria->isDestacada(),
                'publicada' => (int)$categoria->isPublicada(),
                'protegida' => (int)$categoria->isProtegida(),
                'created_at' => $categoria->getCreatedAt()->format('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }

    public function update(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update(Categoria::$tabla)
            ->set('nombre', ':nombre')
            ->set('slug', ':slug')
            ->set('destacada', ':destacada')
            ->set('publicada', ':publicada')
            ->set('updated_at', ':updated_at')
            ->where('id = :id')
            ->setParameters([
                'id' => $categoria->getId(),
                'nombre' => $categoria->getNombre(),
                'slug' => $categoria->getSlug(),
                'destacada' => (int)$categoria->isDestacada(),
                'publicada' => (int)$categoria->isPublicada(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }

    public function delete(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete(Categoria::$tabla)
            ->where('id = :id')
            ->setParameters([
                'id' => $categoria->getId(),
            ]);

        return $qb->execute();
    }

    public function findOneBySlug(string $slug): ?Categoria
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(Categoria::$tabla)
            ->where('slug = :slug')
            ->setParameter(':slug', $slug)
            ->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return $this->categoriaTransformer->transform($st->fetch());
    }

}