<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageTest extends WebTestCase
{
    public function testHomePageHas5Movies(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p.lead', 'Où que vous soyez. Gratuit pour toujours.');

        $filteredCrawler = $crawler->filter('div.movie__poster');
        $this->assertEquals(5, count($filteredCrawler));

    }

    public function testAdd2FavoriteFromHomepage() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');


        $buttonCrawlerNode = $crawler->filter('.movie__favorite > button')->eq(0);

        $form = $buttonCrawlerNode->form();
        
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler = $client->followRedirect();

        $this->assertSelectorTextContains('div.alert-success', 'a été ajouté de votre liste de favoris.');


    }
}
