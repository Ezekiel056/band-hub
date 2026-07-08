<?php

namespace App\Tests\WebTestCase\Controller;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([AppFixtures::class]);
    }

    public function testLoginPageLoadsForAnonymousUser(): void
    {
        $this->client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
    }

    public function testLoginPageRedirectsAuthenticatedUserToHome(): void
    {
        $user = static::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'ezekiel056']);

        $this->client->loginUser($user);
        $this->client->request('GET', '/login');

        $this->assertResponseRedirects('/app');
    }

    public function testLogoutRedirectsAnonymousUserToLogin(): void
    {
        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects();
        $this->assertStringContainsString('/login', $this->client->getResponse()->headers->get('Location'));
    }

    public function testLogoutLogsOutAuthenticatedUser(): void
    {
        $user = static::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => 'ezekiel056']);

        $this->client->loginUser($user);
        $this->client->request('GET', '/logout');

        $this->assertResponseRedirects('/login');

        $this->client->followRedirect();
        $this->client->request('GET', '/app');
        $this->assertResponseRedirects();
    }
}
