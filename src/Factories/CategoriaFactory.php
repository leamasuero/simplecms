<?php

namespace Lebenlabs\SimpleCMS\Factories;

use Lebenlabs\SimpleCMS\Models\Categoria;

class CategoriaFactory
{
    public static function create(array $row): Categoria
    {
        $categoria = new Categoria(
            $row['nombre'],
            (bool)$row['publicada'],
            (bool)$row['destacada'],
            (bool)$row['protegida']
        );

        return $categoria
            ->setId($row['id'])
            ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']))
            ->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['updated_at']));

    }

    public static function transform(array $rows): array
    {
        $categorias = [];
        foreach ($rows as $row) {
            $categorias[] = self::create($row);
        }

        return $categorias;
    }
}

