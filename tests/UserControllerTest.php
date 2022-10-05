<?php
namespace App\Tests\devryx\www\TodoList\tests;

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
        $this->logUser($this->client, 'Test');
    }

    public function testUserListAction()
    {
        $url = $this->urlGenerator->generate('user_list');

        $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main .btn-success', "Edit");
    }

    public function testUserCreateAction()
    {
        $url = $this->urlGenerator->generate('homepage');

        $crawler = $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main', "Créer un utilisateur");
        
        $link = $crawler->selectLink('Créer un utilisateur')->link();
                
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        
        $form = $crawler->selectButton('Ajouter')->form();
        
        $form->setValues([
            'user[username]' => 'José',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[email]' => 'phpunit@test.fr',
        ]);
        
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! L'utilisateur a bien été ajouté.");
    }

    public function testUserEditAction()
    {
        
        $url = $this->urlGenerator->generate('user_list');
        
        $crawler = $this->client->request(Request::METHOD_GET, $url);
        
        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main table', "Edit");
        
        $link = $crawler->selectLink('Edit')->link();
        
        $crawler = $this->client->request(Request::METHOD_GET, $link->getUri());

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main h1', "Modifier");
        
        $form = $crawler->selectButton('Modifier')->form();
        
        $form->setValues([
            'user[username]' => 'Test',
            'user[password][first]' => 'testtest',
            'user[password][second]' => 'testtest',
            'user[email]' => 'phpunit-edit@test.fr',
        ]);
        
        $this->client->submit($form);
        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('#main table', "phpunit-edit@test.fr");
        $this->assertSelectorTextContains('#main .alert-success', "Superbe ! L'utilisateur a bien été modifié");
    }
}