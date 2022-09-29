<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Task;
use TestTools\EmulateLogIn;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Routing\Router;
use Symfony\Component\DomCrawler\Form;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
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

    public function testListAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains("Il n'y a pas encore de tâche enregistrée.", $crawler->filter('div .alert-warning')->text());
    }

    public function testCreateAction()
    {
        $url = $this->urlGenerator->generate('task_create');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Title', $crawler->filter('form')->text());
        $this->assertContains('Content', $crawler->filter('form')->text());

        /** @var Form $form */
        $form = $crawler->selectButton('Ajouter')->form();

        $form->setValues([
            'task[title]' => 'Développez les tests fonctionnels',
            'task[content]' => 'Je suis la description des tests fonctionnels',
        ]);

        $this->client->submit($form);
        $this->client->followRedirect();

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertContains('<strong>Superbe !</strong> La tâche a été bien été ajoutée.', $response->getContent());
    }

    public function testEditAction()
    {
        $url = $this->urlGenerator->generate('task_list');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $link = $crawler->selectLink('Développez les tests fonctionnels')->link();

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

    public function testToggleTaskAction()
    {
        /** @var EntityRepository $taskRepository */
        $taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);

        /** @var Task $task */
        $task = $taskRepository->findOneBy([
            'title' => 'Modification de la tâche : Développez les tests fonctionnels'
        ]);

        $url = $this->urlGenerator->generate('task_toggle', [
            'id' => $task->getId(),
        ]);

        $this->client->followRedirects();
        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertContains('Superbe ! La tâche '.$task->getTitle().' a bien été marquée comme faite.', $crawler->filter('.alert-success')->text());
        $this->assertContains('Marquer non terminée', $crawler->filter('.thumbnail form .btn-success')->text());
    }

    public function testDeleteTaskAction()
    {
        /** @var EntityRepository $taskRepository */
        $taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);

        /** @var Task $task */
        $task = $taskRepository->findOneBy([
            'title' => 'Modification de la tâche : Développez les tests fonctionnels'
        ]);

        $url = $this->urlGenerator->generate('task_delete', [
            'id' => $task->getId(),
        ]);

        $this->client->followRedirects();
        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Superbe ! La tâche a bien été supprimée.', $crawler->filter('.alert-success')->text());
    }
}
