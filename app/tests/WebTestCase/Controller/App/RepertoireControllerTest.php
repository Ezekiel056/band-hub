<?php

namespace App\Tests\WebTestCase\Controller\App;

use App\DataFixtures\AppFixtures;
use App\Entity\Band;
use App\Entity\User;
use App\Enum\SongStatus;
use App\Repository\SongRepository;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RepertoireControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private User $user;
    private Band $band;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([AppFixtures::class]);

        $this->loginAs('ezekiel056');
        $this->setCurrentBand();
    }

    private function loginAs(string $userName): void
    {
        $this->user = static::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneBy(['username' => $userName]);

        $this->client->loginUser($this->user);
    }

    private function setCurrentBand(): void
    {
        $this->band = $this->user->getBandMembers()->first()->getBand();


        $this->client->request('GET', '/app/repertoire');

        $session = $this->client->getRequest()->getSession();
        $session->set('current_band_id', $this->band->getId());
        $session->save();
    }

    public function testRepertoirePageLoads(): void
    {
        $this->client->request('GET', '/app/repertoire');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.navbar-tabs');
    }

    public function testRepertoireDisplaysAllSongs(): void
    {
        $songCount = array_sum(array_column(
            static::getContainer()
                ->get(SongRepository::class)
                ->countByStatus($this->band),
            'total'
        ));

        $this->client->request('GET', '/app/repertoire');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount($songCount, '[data-filter-song]');
    }

    public function testRepertoireFilterByValidatedStatus(): void
    {
        $this->client->request('GET', '/app/repertoire', ['status' => SongStatus::Validated->value]);

        $this->assertResponseIsSuccessful();
        // 7 validated songs in fixtures (see AppFixtures)
        $this->assertSelectorCount(7, '[data-filter-song]');
    }

    public function testRepertoireFilterByLearningStatus(): void
    {
        $this->client->request('GET', '/app/repertoire', ['status' => SongStatus::Learning->value]);

        $this->assertResponseIsSuccessful();
        // 5 learning songs in fixtures
        $this->assertSelectorCount(5, '[data-filter-song]');
    }

    public function testRepertoireFilterByPendingStatus(): void
    {
        $this->client->request('GET', '/app/repertoire', ['status' => SongStatus::Pending->value]);

        $this->assertResponseIsSuccessful();
        // 3 pending songs in fixtures
        $this->assertSelectorCount(3, '[data-filter-song]');
    }

    public function testRepertoireInvalidStatusShowsAllSongs(): void
    {
        $this->client->request('GET', '/app/repertoire', ['status' => 'invalid_status']);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorCount(15, '[data-filter-song]');
    }

    public function testRepertoireDisplaysCorrectPageTitle(): void
    {
        $this->client->request('GET', '/app/repertoire');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Repertoire');
    }

    public function testRepertoireDisplaysTotalSongCount(): void
    {
        $this->client->request('GET', '/app/repertoire');

        $content = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('15', $content);
    }

    public function testRepertoireDisplaysStatusFilterTabs(): void
    {
        $this->client->request('GET', '/app/repertoire');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('a[href*="status=pending"]');
        $this->assertSelectorExists('a[href*="status=learning"]');
        $this->assertSelectorExists('a[href*="status=validated"]');
    }
}

class RepertoireControllerUnauthenticatedTest extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();

        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([AppFixtures::class]);
    }

    private KernelBrowser $client;

    public function testRepertoireRequiresAuthentication(): void
    {
        $this->client->request('GET', '/app/repertoire');

        $this->assertResponseRedirects();
        $this->assertStringContainsString('login', $this->client->getResponse()->headers->get('Location'));
    }
}
