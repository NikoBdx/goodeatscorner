<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Faker\Factory;

#[HasLifecycleCallbacks()]
class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setFirstname('Nicolas');
        $user->setLastname('Brunet');
        $user->setAddress('30 place de Stalingrad');
        $user->setPostalCode('33100');
        $user->setCity('Bordeaux');
        $user->setEmail('n.brunet.webdev@gamil.com');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $faker = Factory::create('fr_FR');
        for ($i = 0; $i <5; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstname);
            $user->setLastname($faker->lastname);
            $user->setAddress($faker->streetAddress);
            $user->setPostalCode($faker->postcode);
            $user->setCity($faker->city);
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordHasher->hashPassword($user, "user.$i"));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
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
