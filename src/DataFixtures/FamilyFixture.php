<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Family;
use App\Entity\Shelf;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

;

#[HasLifecycleCallbacks()]
class FamilyFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {

        $data["Produits Frais"] = [
            "Viandes et vollailes",
            "Fruits et légumes",
        ];
        $data["Boissons"] = [
            "Eaux",
            "Jus de fruits",
        ];
        $data["Epicerie salée"] = [
            "Pâtes riz",
            "Conserves",
        ];
        $data["Epicerie sucrée"] = [
            "Pains, petit-déjeuner",
            "Gateaux, vienoiseries",
        ];
        $data["Entretien, maison"] = [
            "Nettoyage",
            "Fournitures",
        ];

        for( $i=0; $i < count($data["Produits Frais"]); $i++ ) {
            $family = new Family();
            $family->setName($data["Produits Frais"][$i]);
            $family->setShelfId($manager->getRepository(Shelf::class)->findByName("Produits Frais")[0]);
            $manager->persist($family);
        }

        for( $i=0; $i < count($data["Boissons"]); $i++ ) {
            $family = new Family();
            $family->setName($data["Boissons"][$i]);
            $family->setShelfId($manager->getRepository(Shelf::class)->findByName("Boissons")[0]);
            $manager->persist($family);
        }

        for( $i=0; $i < count($data["Epicerie salée"]); $i++ ) {
            $family = new Family();
            $family->setName($data["Epicerie salée"][$i]);
            $family->setShelfId($manager->getRepository(Shelf::class)->findByName("Epicerie salée")[0]);
            $manager->persist($family);
        }

        for( $i=0; $i < count($data["Epicerie sucrée"]); $i++ ) {
            $family = new Family();
            $family->setName($data["Epicerie sucrée"][$i]);
            $family->setShelfId($manager->getRepository(Shelf::class)->findByName("Epicerie sucrée")[0]);
            $manager->persist($family);
        }

        for( $i=0; $i < count($data["Entretien, maison"]); $i++ ) {
            $family = new Family();
            $family->setName($data["Entretien, maison"][$i]);
            $family->setShelfId($manager->getRepository(Shelf::class)->findByName("Entretien, maison")[0]);
            $manager->persist($family);
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
        return ['family'];
    }
}
