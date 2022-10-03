<?php

namespace App\DataFixtures;

use AppBundle\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** @var UserPasswordEncoderInterface $passwordEncoder */
        $passwordEncoder = $this->container->get('security.password_encoder');
        
        $user = new User();

        $user->setEmail('test@test.fr');
        $user->setUsername('Test');
        $user->setPassword($passwordEncoder->encodePassword($user, 'testtest'));

        $manager->persist($user);
        $manager->flush();
    }
}