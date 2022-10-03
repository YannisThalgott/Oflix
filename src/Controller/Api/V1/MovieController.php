<?php

namespace App\Controller\Api\V1;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Classe qui s'occupe des ressources de type Movie
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="movies_get_collection", methods={"GET"})
     */
    public function moviesGetCollection(MovieRepository $movieRepository): Response
    {
        $moviesList = $movieRepository->findAll();

        return $this->json(['movies' => $moviesList], Response::HTTP_OK, [], ['groups' => 'movies_get_collection']);
    }

    /**
     * @Route("/movies/{id}", name="movies_get_item", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function moviesGetItem(Movie $movie = null)
    {
        if ($movie === null) {
            return $this->json(['error' => 'Film non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($movie, Response::HTTP_OK, [], ['groups' => 'movies_get_item']);
    }

    /**
     * @Route("/movies", name="movies_post", methods={"POST"})
     */
    public function moviesPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        $jsonContent = $request->getContent();

        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');

        $errors = $validator->validate($movie);

        if (count($errors) > 0) {
            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath();
                $message = $error->getMessage();
                $cleanErrors[$property][] = $message;
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em = $doctrine->getManager();
        $em->persist($movie);
        $em->flush();

        return $this->json(
            $movie,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_v1_movies_get_item', ['id' => $movie->getId()])
            ],
            ['groups' => 'movies_get_item']
        );
    }
}
