<?php

namespace Lebenlabs\SimpleCMS\Interfaces;

interface Transformer
{
    public function transform(array $row);

    public function transformCollection(iterable $rows): iterable;
}