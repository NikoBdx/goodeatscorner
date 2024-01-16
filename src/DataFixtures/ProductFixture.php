<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Faker\Factory;
;
#[HasLifecycleCallbacks()]
class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ( $i = 0; $i <5; $i++ ) {
            $product = new Product();
            $product->setName($faker->word);
            $product->setDescription($faker->sentence);
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
        return ['user'];
    }
}
