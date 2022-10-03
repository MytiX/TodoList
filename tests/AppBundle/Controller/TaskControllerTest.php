<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\Routing\Router;
use Symfony\Component\DomCrawler\Form;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\AbstractWebTestCase\AbstractWebTestCase;

class TaskControllerTest extends AbstractWebTestCase
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

    public function testTaskListAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains("Il n'y a pas encore de tâche enregistrée.", $crawler->filter('div .alert-warning')->text());
    }

    public function testTaskCreateAction()
    {
        $url = $this->urlGenerator->generate('task_create');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Title', $crawler->filter('form')->text());
        $this->assertContains('Content', $crawler->filter('form')->text());

        /** @var Form $form */
        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues([
            'task[title]' => 'Nouveau tests fonctionnels',
            'task[content]' => 'Je suis la description des tests fonctionnels',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('<strong>Superbe !</strong> La tâche a été bien été ajoutée.', $response->getContent());
    }

    public function testTaskEditAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $link = $crawler->selectLink('Nouveau tests fonctionnels')->link();

        $this->client->click($link);

        $response = $this->client->getResponse();

        $crawler->clear();

        $crawler->addHtmlContent($response->getContent());

        $this->assertContains('Title', $crawler->filter('form')->text());
        $this->assertContains('Content', $crawler->filter('form')->text());
        $this->assertContains('Modifier', $crawler->filter('form')->text());

        $form = $crawler->selectButton('Modifier')->form();

        $form->setValues([
            'task[title]' => 'Modification de la tâche : Développez les tests fonctionnels',
            'task[content]' => 'Je suis la description alternative des tests fonctionnels',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('<strong>Superbe !</strong> La tâche a bien été modifiée.', $response->getContent());

    }

    public function testTaskToggleTaskAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $form = $crawler->selectButton('Marquer comme faite')->form();

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $crawler->clear();
        $crawler->addHtmlContent($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Marquer non terminée', $crawler->selectButton('Marquer non terminée')->text());
    }

    public function testTaskDeleteTaskAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $form = $crawler->selectButton('Supprimer')->form();

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $crawler->clear();
        $crawler->addHtmlContent($response->getContent());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('Superbe ! La tâche a bien été supprimée.', $crawler->filter('.alert-success')->text());
    }
}
