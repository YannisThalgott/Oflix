<?php

namespace App\Service;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Manage user favorites (movies and series) in session
 */
class FavoritesManager
{
    private $session;

    /** @var bool $emptyEnabled Authorise empty list */
    private $emptyEnabled;

    public function __construct(RequestStack $requestStack, $emptyEnabled)
    {
        $this->session = $requestStack->getSession();
        $this->emptyEnabled = $emptyEnabled;
    }

    /**
     * Add or remove movie in favorites list
     * 
     * @param Movie $movie
     * 
     * @return bool true if added, false if removed
     */
    public function toggle(Movie $movie): bool
    {
        $favorites = $this->session->get('favorites');

        if ($favorites != null) {

            if (array_key_exists($movie->getId(), $favorites)) {

                unset($favorites[$movie->getId()]);

                $this->session->set('favorites', $favorites);

                return false;
            }
        }

        $favorites[$movie->getId()] = $movie;

        $this->session->set('favorites', $favorites);

        return true;
    }

    /**
     * Empty favorites list
     */
    public function empty()
    {
        if ($this->emptyEnabled) {
            $this->session->remove('favorites');
            return true;
        }

        return false;
    }
}
