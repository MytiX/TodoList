<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\Routing\Router;
use Symfony\Component\DomCrawler\Form;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var Router */
    private $urlGenerator;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
    }

    public function testLoginAction()
    {
        $url = $this->urlGenerator->generate('login');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, $url);

        /** @var Form $form */
        $form = $crawler->selectButton('Se connecter')->form();

        $form->setValues([
            '_username' => 'Test',
            '_password' => 'testtest',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Bienvenue sur Todo List', $response->getContent());
    }

    public function testLoginActionError()
    {
        $url = $this->urlGenerator->generate('login');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, $url);

        /** @var Form $form */
        $form = $crawler->selectButton('Se connecter')->form();

        $form->setValues([
            '_username' => 'Test',
            '_password' => 'invalid_password',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Invalid credentials.', $response->getContent());
    }

    public function testLogoutCheck()
    {
        $url = $this->urlGenerator->generate('logout');

        $this->client->followRedirects();
        /** @var Crawler $crawler */
        $this->client->request(Request::METHOD_GET, $url);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Nom d\'utilisateur', $this->client->getResponse()->getContent());
    }
}
