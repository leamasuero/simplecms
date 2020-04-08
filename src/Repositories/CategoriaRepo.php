<?php


namespace Lebenlabs\SimpleCMS\Repositories;

use Doctrine\DBAL\Connection;
use Lebenlabs\SimpleCMS\Factories\CategoriaFactory;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Pagerfanta\Adapter\DoctrineDbalSingleTableAdapter;
use Pagerfanta\Pagerfanta;

class CategoriaRepo
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
     * @param int $perPage
     * @return Pagerfanta
     */
    public function buscar(?string $q)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('lebenlabs_simplecms_categorias', 'Categoria')
            ->orderBy('Categoria.id', 'desc');

        if ($q) {
            $qb->where('Categoria.nombre LIKE :q')
                ->setParameter('q', "%{$q}%");
        }

        return new Pagerfanta(new DoctrineDbalSingleTableAdapter($qb, 'Categoria.id'));
    }

    public function find(int $id): Categoria
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from('lebenlabs_simplecms_categorias')
            ->where('id = :id')
            ->setParameter(':id', $id)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return CategoriaFactory::create($st->fetch());
    }

    public function lists(): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('id, nombre')
            ->from('lebenlabs_simplecms_categorias');

        $st = $qb->execute();

        return $st->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    public function insert(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->insert('lebenlabs_simplecms_categorias')
            ->values(
                [
                    'nombre' => ':nombre',
                    'slug' => ':slug',
                    'destacada' => ':destacada',
                    'publicada' => ':publicada',
                    'created_at' => ':created_at',
                    'updated_at' => ':updated_at',
                ]
            )
            ->setParameters([
                'nombre' => $categoria->getNombre(),
                'slug' => $categoria->getSlug(),
                'destacada' => (int)$categoria->getDestacada(),
                'publicada' => (int)$categoria->getPublicada(),
                'created_at' => $categoria->getCreatedAt()->format('Y-m-d'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }

    public function update(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->update('lebenlabs_simplecms_categorias')
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
                'destacada' => (int)$categoria->getDestacada(),
                'publicada' => (int)$categoria->getPublicada(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        return $qb->execute();
    }

    public function delete(Categoria $categoria)
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->delete('lebenlabs_simplecms_categorias')
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
            ->from('lebenlabs_simplecms_categorias')
            ->where('slug = :slug')
            ->setParameter(':slug', $slug)->setMaxResults(1);

        $st = $qb->execute();

        if ($st->rowCount() === 0) {
            return null;
        }

        return CategoriaFactory::create($st->fetch());
    }

}