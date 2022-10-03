<?php

namespace App\Controller\Api\V1;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Classe qui s'occupe des ressources de type Genre
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/genres", name="get_genres_collection", methods={"GET"})
     */
    public function genresGetCollection(GenreRepository $genreRepository)
    {
        $genresList = $genreRepository->findAll();

        return $this->json(['genres' => $genresList], Response::HTTP_OK, [], ['groups' => 'genres_get_collection']);
    }

    /**
     * @Route("/genres/{id}/movies", name="genres_get_movies_collection", methods={"GET"})
     */
    public function moviesCollectionByGenre(Genre $genre = null): Response
    {
        // 404 ?
        if ($genre === null) {
            return $this->json(['error' => 'Non non non !'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $genre,
            Response::HTTP_OK,
            [],
            ['groups' => ['genres_get_movies_collection', 'movies_get_collection']]);
    }
}
