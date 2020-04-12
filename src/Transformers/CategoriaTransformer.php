<?php

namespace Lebenlabs\SimpleCMS\Transformers;

use Lebenlabs\SimpleCMS\Interfaces\Transformer;
use Lebenlabs\SimpleCMS\Models\Categoria;

class CategoriaTransformer implements Transformer
{
    public function transform(array $row): Categoria
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

    public function transformCollection(iterable $rows): iterable
    {
        $categorias = [];
        foreach ($rows as $row) {
            $categorias[] = self::transform($row);
        }

        return $categorias;
    }
}

