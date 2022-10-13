<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Tests\AbstractWebTestCase\AbstractWebTestCase;

class UserControllerTest extends AbstractWebTestCase
{
    /** @var KernelBrowser */
    private $client;

    /** @var Router */
    private $urlGenerator;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
    }

    public function testUserListAction()
    {
        $this->logUser($this->client, 'Admin');
        $url = $this->urlGenerator->generate('user_list');

        $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main .btn-success', "Edit");
    }

    public function testUserCreateAction()
    {
        $url = $this->urlGenerator->generate('login');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#header', "Créer un compte");
        
        $link = $crawler->selectLink('Créer un compte')->link();
                
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Continuer')->form();
        
        $form->setValues([
            'user[username]' => 'José',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[email]' => 'phpunit@test.fr'
        ]);
        
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! Votre compte à bien été crée.");
    }

    public function testUserEditAction()
    {
        $this->logUser($this->client, 'Admin');

        $url = $this->urlGenerator->generate('user_list');
        
        $crawler = $this->client->request(Request::METHOD_GET, $url);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main table', "Edit");
        
        $link = $crawler->selectLink('Edit')->link();
        
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Modifier')->form();
        
        $form->setValues([
            'user_admin[username]' => 'Edited Test',
            'user_admin[password][first]' => 'testtest',
            'user_admin[password][second]' => 'testtest',
            'user_admin[email]' => 'phpunit-edit@test.fr',
        ]);
        
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main table', "phpunit-edit@test.fr");
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }
}