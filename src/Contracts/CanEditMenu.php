<?php

namespace Lebenlabs\SimpleCMS\Contracts;

interface CanEditMenu
{
    /**
     * Returns true if the Entity can edit Menu
     *
     * @return boolean
     */
    public function canEditMenu();

}
