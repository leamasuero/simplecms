<?php

namespace Lebenlabs\SimpleCMS\Transformers;

use Lebenlabs\SimpleCMS\Interfaces\Transformer;
use Lebenlabs\SimpleCMS\Models\Categoria;
use Lebenlabs\SimpleCMS\Models\Publicacion;

class PublicacionTransformer implements Transformer
{
    public function transform(array $row): Publicacion
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
            ->setNotificable((bool)$row['notificable'])
            ->setCategoria((new Categoria($row['categoria_nombre']))->setId($row['categoria_id']));

        if ($row['notificada_at']) {
            $publicacion->setNotificadaAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['notificada_at']));
        }

        return $publicacion
            ->setId($row['id'])
            ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']))
            ->setUpdatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row['updated_at']));

    }

    public function transformCollection(iterable $rows): iterable
    {
        $publicaciones = [];
        foreach ($rows as $row) {
            $publicaciones[] = self::transform($row);
        }

        return $publicaciones;
    }
}

