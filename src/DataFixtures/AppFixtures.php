<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Casting;
use App\Service\MySlugger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;

class AppFixtures extends Fixture
{
    // Le Slugger à utiliser dans la classe de Fixture
    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        $faker->seed(2022);

        $oflixProvider = new OflixProvider();
        $faker->addProvider($oflixProvider);

        // Users
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$.PJiDK3kq2C4owW5RW6Z3ukzRc14TJZRPcMfXcCy9AyhhA9OMK3Li');
        $manager->persist($admin);

        $managerUser = new User();
        $managerUser->setEmail('manager@manager.com');
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setPassword('$2y$13$/U5OgXbXusW7abJveoqeyeTZZBDrq/Lzh8Gt1RXnEDbT2xJqbv3vi');
        $manager->persist($managerUser);

        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$ZqCHV23K0KMWmCxntdDlmOocuxuuSOXeT7nfKy2ZbE2vFC1VS3Q..');
        $manager->persist($user);

        // Les genres

        $genresList = [];

        for ($i = 1; $i <= 20; $i++) {

            $genre = new Genre();
            $genre->setName($faker->unique()->movieGenre());

            $genresList[] = $genre;

            $manager->persist($genre);
        }

        // Persons

        $personsList = [];

        for ($i = 1; $i <= 100; $i++) {

            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());

            $personsList[] = $person;

            $manager->persist($person);
        }

        // Les films
        for ($m = 1; $m <= 10; $m++) {

            $movie = new Movie();
            $movie->setSummary($faker->paragraph());
            $movie->setSynopsis($faker->text(300));
            // On a une chance sur 2 d'avoir un film
            $movie->setType($faker->randomElement(['Film', 'Série']));
            $movie->setTitle($faker->unique()->movieTitle());

            // Option Ecriture 1
            // On génère le slug à partir du titre
            $slug = $this->slugger->slugify($movie->getTitle());
            $movie->setSlug($slug);

            // Option Ecriture 2
            // En décomposé
            // Le titre
            // $movieTitle = $movie->getTitle();
            // Le titre "slugifié"
            // $slugTitle = $this->slugger->slugify($movieTitle);
            // Le slug mis à jour dans l'entité
            // $movie->setSlug($slugTitle);

            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            // Entre 30 min et 263 minutes
            $movie->setDuration($faker->numberBetween(30, 263)); 
            $movie->setPoster('https://picsum.photos/id/' . $faker->numberBetween(1, 100) . '/450/300');
            // 1 chiffre après la virgule, entre 1 et 5
            $movie->setRating($faker->randomFloat(1, 1, 5));

            // Seasons
            if ($movie->getType() === 'Série') {
                for ($j = 1; $j <= mt_rand(3, 8); $j++) {
                    $season = new Season();
                    $season->setNumber($j);
                    $season->setEpisodesNumber(mt_rand(6, 24));
                    $season->setMovie($movie);

                    $manager->persist($season);
                }
            }

            for ($g = 1; $g <= mt_rand(1, 3); $g++) {

                $randomGenre = $genresList[mt_rand(0, count($genresList) - 1)];
                $movie->addGenre($randomGenre);
            }

            shuffle($personsList);

            for ($c = 1; $c <= mt_rand(3, 5); $c++) {

                $casting = new Casting();
                $casting->setRole($faker->name());
                $casting->setCreditOrder($c);

                $casting->setMovie($movie);
                $randomPerson = $personsList[$c];
                $casting->setPerson($randomPerson);

                $manager->persist($casting);
            }

            for ($j = 0; $j < mt_rand(15, 20); $j++) {
                $review = new Review();

                $review
                    ->setRating(mt_rand(2, 5))
                    ->setUsername($faker->userName())
                    ->setEmail($faker->email())
                    ->setContent($faker->realTextBetween(100, 300))
                    ->setReactions($faker->randomElements([
                        'smile',
                        'cry',
                        'think',
                        'sleep',
                        'dream',
                    ], mt_rand(1, 4)))
                    ->setWatchedAt(new DateTimeImmutable('-' . mt_rand(1, 50) . ' years'))
                    ->setMovie($movie);

                $manager->persist($review);
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
