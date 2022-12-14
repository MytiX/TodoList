<?php

namespace App\Tests;

use Symfony\Component\DomCrawler\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use App\Tests\AbstractWebTestCase\AbstractWebTestCase;

class TaskControllerTest extends AbstractWebTestCase
{
    /** @var KernelBrowser */
    private $client;

    /** @var Router */
    private $urlGenerator;
        
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
        $this->logUser($this->client, 'Test');
    }

    public function testTaskListAction()
    {
        $this->logUser($this->client, 'Test');

        $url = $this->urlGenerator->generate('homepage');

        $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-warning', "Vous n'avez pas de tâche en cours.");
    }

    public function testTaskCreateAction()
    {
        $url = $this->urlGenerator->generate('task_create');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main form', "Titre");
        $this->assertSelectorTextContains('#main form', "Contenu");

        /** @var Form $form */
        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues([
            'task[title]' => 'Nouveau tests fonctionnels',
            'task[content]' => 'Je suis la description des tests fonctionnels',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! La tâche a été bien été ajoutée.");
    }

    public function testTaskEditAction()
    {
        $url = $this->urlGenerator->generate('homepage');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $link = $crawler->selectLink('Nouveau tests fonctionnels')->link();

        $this->client->click($link);

        $response = $this->client->getResponse();

        $crawler->clear();

        $crawler->addHtmlContent($response->getContent());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main form', "Titre");
        $this->assertSelectorTextContains('#main form', "Contenu");
        $this->assertSelectorTextContains('#main form', "Modifier");

        $form = $crawler->selectButton('Modifier')->form();

        $form->setValues([
            'task[title]' => 'Modification de la tâche : Développez les tests fonctionnels',
            'task[content]' => 'Je suis la description alternative des tests fonctionnels',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! La tâche a bien été modifiée.");

    }
    
    public function testTaskToggleTaskAction()
    {
        $url = $this->urlGenerator->generate('homepage');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $form = $crawler->selectButton('Terminée')->form();

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe !");
    }

    public function testTaskDeleteTaskAction()
    {
        $url = $this->urlGenerator->generate('task_list_finish');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $form = $crawler->selectButton('Supprimer')->form();

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! La tâche a bien été supprimée.");
    }
}