<?php

namespace App\Tests\Service;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\HttpClient;

class OmdbApiTest extends KernelTestCase
{
    public function testFetch(): void
    {
        $out = static::getContainer()->get(OmdbApi::class);

        $movie = $out->fetch('the intouchables');
        $this->assertArrayHasKey('Poster', $movie);
        $this->assertArrayHasKey('imdbID', $movie);

        $movie = $out->fetch('intouchargaerbvaerbgables');

        $this->assertArrayHasKey('Response', $movie);
        $this->assertSame('False', $movie['Response']);
    }
    
    public function testFetchPoster(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $omdbApi = static::getContainer()->get(OmdbApi::class);

        $poster = $omdbApi->fetchPoster('the intouchables');
        $this->assertSame('https://m.media-amazon.com/images/M/MV5BMTYxNDA3MDQwNl5BMl5BanBnXkFtZTcwNTU4Mzc1Nw@@._V1_SX300.jpg', $poster);

        $poster = $omdbApi->fetchPoster('intouchargaerbvaerbgables');
        $this->assertNull($poster);
    }

}
