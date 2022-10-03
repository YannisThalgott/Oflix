<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractController
{
    /**
     * @Route("/movie/{slug}/review/add", name="review_add")
     */
    public function add(Movie $movie = null, Request $request, ManagerRegistry $doctrine): Response
    {
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review->setMovie($movie);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('movie_show', ['slug' => $movie->getSlug()]);
        }

        return $this->renderForm('front/review/add.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }
}
