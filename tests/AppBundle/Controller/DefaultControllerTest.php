<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TestTools\EmulateLogIn;

class DefaultControllerTest extends WebTestCase
{
    /** @var Client */
    private $client = null;

    /** @var Router */
    private $urlGenerator;

    public function setUp() 
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
    }

    public function testIndexAction()
    {
        EmulateLogIn::logUserTest($this->client);

        $url = $this->urlGenerator->generate('homepage');

        /** @var Crawler $crawler */
        $crawler = $this->client->request(Request::METHOD_GET, $url);
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Bienvenue sur Todo List', $crawler->filter('.container h1')->text());

        $this->assertContains('Créer un utilisateur', $crawler->selectLink('Créer un utilisateur')->text());
        $this->assertContains('Se déconnecter', $crawler->selectLink('Se déconnecter')->text());
        $this->assertContains('Créer une nouvelle tâche', $crawler->selectLink('Créer une nouvelle tâche')->text());
        $this->assertContains('Consulter la liste des tâches à faire', $crawler->selectLink('Consulter la liste des tâches à faire')->text());
        $this->assertContains('Consulter la liste des tâches terminées', $crawler->selectLink('Consulter la liste des tâches terminées')->text());
    }
}
