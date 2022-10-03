<?php

namespace App\EventListener;

use App\Entity\Movie;
use App\Service\MySlugger;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class MovieSluggerListener
{

    private $slugger;

    public function __construct(MySlugger $slugger)
    {
        $this->slugger = $slugger;
    }

    public function setSlug(Movie $movie, LifecycleEventArgs $event): void
    {
        $movie->setSlug($this->slugger->slugify($movie->getTitle()));
    }
}