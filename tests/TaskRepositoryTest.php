<?php

namespace App\Tests;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskRepositoryTest extends KernelTestCase
{
    public function testFindAllAnonymousAndTheseTask()
    {
        self::bootKernel();

        /** @var TaskRepository */
        $taskRepository = static::getContainer()->get(TaskRepository::class);

        $this->assertNotEmpty($taskRepository->findAllAnonymousAndTheseTask(1));
    }
}