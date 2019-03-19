<?php

namespace Lebenlabs\SimpleCMS\Contracts;

use Lebenlabs\SimpleCMS\Models\Publicacion;

interface CanViewPublicacion
{
    /**
     * Returns true if the Entity can manage Publicaciones
     *
     * @return boolean
     */
    public function canViewPublicacion(Publicacion $publicacion);

}
