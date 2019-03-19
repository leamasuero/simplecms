<?php

namespace Lebenlabs\SimpleCMS\Contracts;

interface CanEditMenuItem
{
    /**
     * Returns true if the Entity can edit Menu Item
     *
     * @return boolean
     */
    public function canEditMenuItem();

}
