<?php

namespace Lebenlabs\SimpleCMS\Adapters;

use Lebenlabs\SimpleCMS\Interfaces\Transformer;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Doctrine\DBAL\Query\QueryBuilder;

class SimpleCmsAdapter extends DoctrineDbalAdapter
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var Transformer
     */
    private $transformer;

    public function __construct(QueryBuilder $queryBuilder, callable $countQueryBuilderModifier, Transformer $transformer)
    {
        $this->transformer = $transformer;
        $this->queryBuilder = $queryBuilder;

        parent::__construct($queryBuilder, $countQueryBuilderModifier);

    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $qb = clone $this->queryBuilder;
        $result = $qb->setMaxResults($length)
            ->setFirstResult($offset)
            ->execute();

        return $this->transformer->transformCollection($result->fetchAll());
    }
}