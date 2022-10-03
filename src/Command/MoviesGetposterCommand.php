<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\OmdbApi;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoviesGetposterCommand extends Command
{
    protected static $defaultName = 'app:movies:getposter';
    protected static $defaultDescription = 'Update movie poster from OMDBAPI';

    private $movieRepository;
    private $doctrine;
    private $omdbApi;


    public function __construct(
        MovieRepository $movieRepository,
        ManagerRegistry $doctrine,
        OmdbApi $omdbApi
    ) {
        $this->movieRepository = $movieRepository;
        $this->doctrine = $doctrine;
        $this->omdbApi = $omdbApi;


        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
             ->addArgument('title', InputArgument::OPTIONAL, 'Movie title to get')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $movieTitle = $input->getArgument('title');

        $verbose = $input->getOption('verbose');

        $io->info('Updating posters...');

        if ($movieTitle !== null) {

            $movie = $this->movieRepository->findOneByTitle($movieTitle);

            if ($movie === null) {
                $io->error('Film non trouvé');

                return COMMAND::INVALID;
            }

            $movies = [$movie];

        } else {
            // Récupérer tous les films (via MovieRepository + findAll())
            $movies = $this->movieRepository->findAll();
        }

        foreach ($movies as $movie) {
            $poster = $this->omdbApi->fetchPoster($movie->getTitle());

            if ($verbose) {
                $io->note($movie->getTitle());
            }

            $movie->setPoster($poster);
        }

        $this->doctrine->getManager()->flush();

        $io->success('Posters updated !');

        return Command::SUCCESS;
    }
}
