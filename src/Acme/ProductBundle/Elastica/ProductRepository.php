<?php

namespace Acme\ProductBundle\Elastica;

use Elastica\Filter;
use Elastica\Query;
use FOS\ElasticaBundle\Repository;

class ProductRepository extends Repository
{
    public function searchProducts($data)
    {
        $query = new Query\Bool();
        if (isset($data['query'])) {
            $titleQuery = new Query\Text();
            $titleQuery->setFieldQuery('title', $data['query']);
            $descQuery = new Query\Text();
            $descQuery->setFieldQuery('description', $data['query']);
            $query->addShould($titleQuery);
            $query->addShould($descQuery);
            $query->setMinimumNumberShouldMatch(1);
        }
        if (isset($data['width']['from']) || isset($data['width']['to'])) {
            $args = array();
            if (isset($data['width']['from'])) {
                $args['gte'] = $data['width']['from'];
            }
            if (isset($data['width']['to'])) {
                $args['lte'] = $data['width']['to'];
            }
            $query->addMust(new Query\Range('width', $args));
        }

        return $this->find($query, 100);
    }
}
