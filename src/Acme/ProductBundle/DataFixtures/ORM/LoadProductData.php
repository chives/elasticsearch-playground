<?php

namespace Acme\ProductBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\Doctrine;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\ORM\Doctrine\Populator;

class LoadProductData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $generator = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++) {
            $populator = new Populator($generator, $manager);
            $populator->addEntity('AcmeProductBundle:Product', 1000, array(
                'width' => function() use ($generator) { return $generator->randomNumber(3); },
                'height' => function() use ($generator) { return $generator->randomNumber(3); },
                'weight' => function() use ($generator) { return $generator->randomNumber(2); },
                'description' => function() use ($generator) { return $generator->text(2000); },
            ));
            $populator->execute();
            $manager->clear();
        }
    }
}
