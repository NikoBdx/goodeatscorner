<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Shelf;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
;

#[HasLifecycleCallbacks()]
class ShelfFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Produits Frais",
            "Boissons",
            "Epicerie salée",
            "Epicerie sucrée",
            "Entretien, maison",
        ];
        for( $i=0; $i<count($data); $i++ ) {
            $shelf = new Shelf();
            $shelf->setName($data[$i]);
            $manager->persist($shelf);
        }
        $manager->flush();
    }

    /**
     * Elle retourne la liste des groupes auxels vous voulez associer votre fixture
     *
     * @return array|string[]
     */
    public static function getGroups(): array
    {
        return ['shelf'];
    }


}
