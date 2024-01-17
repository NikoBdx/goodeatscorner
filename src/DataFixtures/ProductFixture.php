<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Faker\Factory;
use App\Entity\Family;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
;
#[HasLifecycleCallbacks()]
class ProductFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $families = $manager->getRepository(Family::class)->findAll();

        $faker = Factory::create('fr_FR');

        for ( $i = 0; $i < 50; $i++ ) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
            $product->setDescription($faker->sentence);
            $product->setStock(100);
            $product->setPrice($faker->randomFloat(1, 5, 30));
            $product->setImage("dummy_250x250.png");
            $product->setFamily($families[0]);
            $manager->persist($product);
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
        return ['products'];
    }
}
