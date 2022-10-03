<?php

namespace App\Controller\Api\V1;

use App\Entity\Casting;
use App\Repository\CastingRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Classe qui s'occupe des ressources de type Casting
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class CastingController extends AbstractController
{
    /**
     * @Route("/castings", name="get_castings_collection", methods={"GET"})
     */
    public function castingsGetCollection(CastingRepository $castingRepository)
    {
        $castingsList = $castingRepository->findAll();

        return $this->json(['castings' => $castingsList], Response::HTTP_OK, [], ['groups' => 'castings_get_collection']);
    }

        /**
     * @Route("/castings/{id}/movies", name="castings_get_movies_collection", methods={"GET"})
     */
    public function moviesCollectionByCasting(Casting $casting = null): Response
    {
        // 404 ?
        if ($casting === null) {
            return $this->json(['error' => 'Non non non !'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $casting,
            Response::HTTP_OK,
            [],
            ['groups' => ['castings_get_movies_collection', 'movies_get_collection']]);
    }
}
