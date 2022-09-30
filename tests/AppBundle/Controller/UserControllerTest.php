<?php

namespace Tests\AppBundle\Controller;

use TestTools\EmulateLogIn;
use Symfony\Component\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    /** @var Client */
    private $client;

    /** @var Router */
    private $urlGenerator;

    public function setUp()
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
        EmulateLogIn::logUserTest($this->client);
    }

    public function testUserListAction()
    {
        $url = $this->urlGenerator->generate('user_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Edit', $crawler->filter('.btn-success')->text());
    }
}