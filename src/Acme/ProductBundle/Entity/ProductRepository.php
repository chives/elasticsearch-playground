<?php

namespace Acme\ProductBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function createSearchQueryBuilder($data)
    {
        $qb = $this->createQueryBuilder('p');

        if ($data['query']) {
            $qb->andWhere($qb->expr()->orX(
                sprintf("p.name LIKE '%%%s%%'", $data['query']),
                sprintf("p.description LIKE '%%%s%%'", $data['query'])
            ));
        }

        if (isset($data['width']['from']) && isset($data['width']['to'])) {
            $qb->andWhere($qb->expr()->between('p.width', $data['width']['from'], $data['width']['to']));
        } else if (isset($data['width']['from'])) {
            $qb->andWhere($qb->expr()->gte('p.width', $data['width']['from']));
        } else if (isset($data['width']['to'])) {
            $qb->andWhere($qb->expr()->lte('p.width', $data['width']['to']));
        }

        if (isset($data['height']['from']) && isset($data['height']['to'])) {
            $qb->andWhere($qb->expr()->between('p.height', $data['height']['from'], $data['height']['to']));
        } else if (isset($data['height']['from'])) {
            $qb->andWhere($qb->expr()->gte('p.height', $data['height']['from']));
        } else if (isset($data['height']['to'])) {
            $qb->andWhere($qb->expr()->lte('p.height', $data['height']['to']));
        }

        if (isset($data['weight']['from']) && isset($data['weight']['to'])) {
            $qb->andWhere($qb->expr()->between('p.weight', $data['weight']['from'], $data['weight']['to']));
        } else if (isset($data['weight']['from'])) {
            $qb->andWhere($qb->expr()->gte('p.weight', $data['weight']['from']));
        } else if (isset($data['weight']['to'])) {
            $qb->andWhere($qb->expr()->lte('p.weight', $data['weight']['to']));
        }

        return $qb;
    }
} 
