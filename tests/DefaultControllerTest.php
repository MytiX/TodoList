<?php

namespace App\Tests;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\AbstractWebTestCase\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends AbstractWebTestCase
{
    public function testDefaultIndexAction(): void
    {
        $client = static::createClient();

        $urlGenerator = $client->getContainer()->get('router');

        $this->logUser($client, 'Test');

        $url = $urlGenerator->generate('homepage');

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main h1', 'Bienvenue sur Todo List');
    }
}
