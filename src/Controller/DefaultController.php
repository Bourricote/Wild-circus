<?php

namespace App\Controller;

use App\Repository\PerformanceRepository;
use App\Repository\TourRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PerformanceRepository $performanceRepository, TourRepository $tourRepository)
    {
        $performances = $performanceRepository->findBy([], ['createdAt' => 'ASC'], 3, 0 );

        $nextTour = $tourRepository->findBy([], ['date' => 'ASC'], 1, 0);

        return $this->render('home.html.twig', [
            'performances' => $performances,
            'next_tour' => $nextTour,
        ]);
    }
}
