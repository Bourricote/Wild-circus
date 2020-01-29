<?php

namespace App\Controller;

use App\Repository\PerformanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PerformanceRepository $performanceRepository)
    {
        $performances = $performanceRepository->findAll();

        return $this->render('home.html.twig', [
            'performances' => $performances,
        ]);
    }
}
