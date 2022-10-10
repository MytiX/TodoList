<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testUserTypeForm()
    {
        $formData = [
            'username' => 'José',
            'password' => [
                'first' => 'testtest',
                'second' => 'testtest',
            ],
            'email' => 'test@test.fr',
            'roles' => [],
        ];

        $user = new User();

        $form = $this->factory->create(UserType::class, $user);

        $userExpected = new User();

        $userExpected->setEmail($formData['email'])
                    ->setUsername($formData['username'])
                    ->setPassword($formData['password']['first'])
                    ->setRoles($formData['roles']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($userExpected, $user);
    }
}

