<?php

namespace App\Tests;

use App\Entity\Task;
use App\Form\TaskType;
use DateTime;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testTaskTypeForm()
    {
        $formData = [
            'title' => 'Je suis une tâche',
            'content' => 'Je suis la description de la tâche',
        ];

        $task = new Task();

        $form = $this->factory->create(TaskType::class, $task);

        $taskExpected = new Task();

        $taskExpected->setTitle($formData['title'])
                    ->setContent($formData['content']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($taskExpected, $task);
    }
}

