<?php

namespace AdminBundle\DataFixtures;

use AppBundle\Entity\Player;
use AppBundle\Entity\PlayerStat;
use AppBundle\Entity\Nationality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PlayerFixtures extends Fixture
{
    /*public function load(ObjectManager $manager)
    {
        $jsonPlayer   = $this->kernel->getRootDir() . "/../web/data/players.json";
        $dataPlayer = json_decode($jsonPlayer);
        dump($jsonPlayer);die;
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Player();
            $product->setName('product '.$i);
            $product->setPrice(mt_rand(10, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }*/
}