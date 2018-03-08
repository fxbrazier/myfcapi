<?php

namespace AdminBundle\DataFixtures;

use AppBundle\Entity\Club;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class ClubFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $club = new Club();
        $club->setName('Arsenal');
        $club->setBlason("https://www.arsenal.com/themes/custom/arsenal_main/logo.svg");
        $manager->persist($club);
        $manager->flush();
    }
}