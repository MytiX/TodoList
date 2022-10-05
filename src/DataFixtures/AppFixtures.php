<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager)
    {        
        $user = new User();

        $user->setEmail('test@test.fr');
        $user->setUsername('Test');
        $user->setPassword($this->passwordHasher->hashPassword($user, 'testtest'));

        $manager->persist($user);
        $manager->flush();
    }
}