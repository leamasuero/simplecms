<?php

namespace Lebenlabs\SimpleCMS\Factories;

use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Models\Publicacion;

class PublicacionFactory
{
    public static function create(array $row): Publicacion
    {
        $publicacion = new Publicacion(
            $row['titulo'],
            $row['extracto'],
            $row['cuerpo']
        );

        $publicacion
            ->setFechaPublicacion(\DateTime::createFromFormat('Y-m-d H:i:s', $row['fecha_publicacion']))
            ->setDestacada((bool)$row['destacada'])
            ->setPrivada((bool)$row['privada'])
            ->setPublicada((bool)$row['publicada'])
            ->setProtegida((bool)$row['protegida'])
            ->setCategoria((new Categoria($row['categoria_nombre']))->setId($row['categoria_id']));

        return $publicacion
            ->setId($row['id'])
            ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']))
            ->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['updated_at']));

    }

    public static function transform(array $rows): array
    {
        $publicaciones = [];
        foreach ($rows as $row) {
            $publicaciones[] = self::create($row);
        }

        return $publicaciones;
    }
}

