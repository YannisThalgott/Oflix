<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\MySlugger;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MoviesSlugifyCommand extends Command
{

    protected static $defaultName = 'app:movies:slugify';

    protected static $defaultDescription = 'Set a slug for each movie in the database';

    private $movieRepository;
    private $mySlugger;
    private $doctrine;


    public function __construct(
        MovieRepository $movieRepository,
        MySlugger $mySlugger,
        ManagerRegistry $doctrine
    ) {
        $this->movieRepository = $movieRepository;
        $this->mySlugger = $mySlugger;
        $this->doctrine = $doctrine;

        parent::__construct();
    }
    
    /**
     * Configuration de la commande
     */
    protected function configure(): void
    {
    }

    /**
     * Exécution de la commande
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Updating slugs...');

        // Récupérer tous les films (via MovieRepository + findAll())
        $movies = $this->movieRepository->findAll();

        // Pour chaque film
        foreach ($movies as $movie) {
            // On slugifie le titre via MySlugger
            $slug = $this->mySlugger->slugify($movie->getTitle());
            $movie->setSlug($slug);
        }

        $this->doctrine->getManager()->flush();

        $io->success('Movies slug updated.');

        return Command::SUCCESS;
    }
}
