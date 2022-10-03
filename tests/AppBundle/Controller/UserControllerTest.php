<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AbstractWebTestCase\AbstractWebTestCase;

class UserControllerTest extends AbstractWebTestCase
{
    /** @var Client */
    private $client;

    /** @var Router */
    private $urlGenerator;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
        $this->logUser($this->client, 'Test', 'testtest');
    }

    public function testUserListAction()
    {
        $url = $this->urlGenerator->generate('user_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Edit', $crawler->filter('.btn-success')->text());
    }

    public function testUserCreateAction()
    {
        $url = $this->urlGenerator->generate('homepage');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Créer un utilisateur', $crawler->filter('.container .row a')->text());
        
        $link = $crawler->selectLink('Créer un utilisateur')->link();
                
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Ajouter')->form();
        
        $form->setValues([
            'user[username]' => 'José',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[email]' => 'phpunit@test.fr',
        ]);
        
        $this->client->followRedirects();
        $this->client->submit($form);
        
        $crawler->clear();
        $crawler->addContent($this->client->getResponse()->getContent());
        
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains("Superbe ! L'utilisateur a bien été ajouté.", $crawler->filter('.alert-success')->text());
    }

    public function testUserEditAction()
    {
        $this->client->followRedirects();

        $url = $this->urlGenerator->generate('user_list');
        
        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Edit', $crawler->filter('.table')->text());
        
        $link = $crawler->selectLink('Edit')->link();
        
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());
        
        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Modifier', $crawler->filter('h1')->text());

        $form = $crawler->selectButton('Modifier')->form();

        $form->setValues([
            'user[username]' => 'Test',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[email]' => 'phpunit-edit@test.fr',
        ]);
        
        $this->client->submit($form);

        $crawler->clear();
        $crawler->addHtmlContent($this->client->getResponse()->getContent());

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('phpunit-edit@test.fr', $crawler->filter('.table')->text());
        $this->assertContains("Superbe ! L'utilisateur a bien été modifié", $crawler->filter('.alert-success')->text());
    }
}