<?php

namespace App\Tests\AbstractWebTestCase;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{    
    /**
     * Log User
     *
     * @param KernelBrowser $client
     * @param string $username of User
     * @return void
     */
    public static function logUser($client, $username)
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        
        $testUser = $userRepository->findOneBy([
            'username' => $username
        ]);

        $client->loginUser($testUser);
    }
}