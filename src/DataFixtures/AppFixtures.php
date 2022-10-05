<?php

namespace App\DataFixtures;

use App\Entity\Task;
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
        $task = new Task();

        $user->setEmail('test@test.fr')
            ->setUsername('Test')
            ->setPassword($this->passwordHasher->hashPassword($user, 'testtest'));

        $task->setTitle("Test")
            ->setContent("test")
            ->setUser($user);

        $manager->persist($user);
        $manager->persist($task);
        $manager->flush();
    }
}