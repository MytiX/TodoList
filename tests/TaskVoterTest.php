<?php 

namespace App\Tests;

use App\Entity\Task;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Tests\AbstractWebTestCase\AbstractWebTestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TaskVoterTest extends AbstractWebTestCase
{
    /** @var KernelBrowser */
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUser($this->client, 'Test');
    }

    public function testTaskVoterSuccess()
    {
        $container = $this->client->getContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        $user = $userRepository->findOneBy([
            'username' => 'Test',
        ]);

        $task = new Task();
        $task->setUser($user);
        
        /** @var AuthorizationChecker $authorization */
        $authorization = $container->get('security.authorization_checker');

        $this->assertEquals(true, $authorization->isGranted("EDIT", $task));
        $this->assertEquals(true, $authorization->isGranted("TOGGLE", $task));
        $this->assertEquals(true, $authorization->isGranted("DELETE", $task));
    }

    public function testTaskVoterFailure()
    {
        $container = $this->client->getContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        $user = $userRepository->findOneBy([
            'username' => 'Admin',
        ]);

        $task = new Task();
        $task->setUser($user);
        
        /** @var AuthorizationChecker $authorization */
        $authorization = $container->get('security.authorization_checker');

        $this->assertEquals(false, $authorization->isGranted("EDIT", $task));
        $this->assertEquals(false, $authorization->isGranted("TOGGLE", $task));
        $this->assertEquals(false, $authorization->isGranted("DELETE", $task));
    }
}