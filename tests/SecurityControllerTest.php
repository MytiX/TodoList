<?php

namespace App\Tests;

use Symfony\Component\Routing\Router;
use Symfony\Component\DomCrawler\Form;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
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

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main h1', "Bienvenue sur Todo List");
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

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-danger', "Invalid credentials.");
    }

    public function testLogoutCheck()
    {
        $url = $this->urlGenerator->generate('logout');

        /** @var Crawler $crawler */
        $this->client->request(Request::METHOD_GET, $url);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main form', "Nom d'utilisateur");
    }
}