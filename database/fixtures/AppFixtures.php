<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private ParameterBagInterface $parameterBag) {}

    public function load(ObjectManager $manager)
    {        
        $env = $this->parameterBag->get('app.env');

        $user = new User();
        
        $user->setEmail('test@test.fr')
        ->setUsername('Test')
        ->setPassword($this->passwordHasher->hashPassword($user, 'testtest'));
        
        $admin = new User();
        
        $admin->setEmail('admin@test.fr')
        ->setUsername('Admin')
        ->setPassword($this->passwordHasher->hashPassword($user, 'testtest'))
        ->setRoles(['ROLE_ADMIN']);

        
        if ($env !== 'test') {
            $task = new Task();
            $task->setTitle("Test")
                ->setContent("test")
                ->setUser($user);
            $manager->persist($task);
        }

        $manager->persist($admin);
        $manager->persist($user);
        $manager->flush();
    }
}