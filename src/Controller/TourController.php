<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Tour;
use App\Entity\TourSearch;
use App\Form\BookingType;
use App\Form\TourSearchType;
use App\Form\TourType;
use App\Repository\TourRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tour")
 */
class TourController extends AbstractController
{

    /**
     * @Route("/", name="all_tours")
     * @param TourRepository $tourRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function showAll(TourRepository $tourRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = new TourSearch();

        $form = $this->createForm(TourSearchType::class, $search);
        $form->handleRequest($request);

        $tours = $paginator->paginate(
            $tourRepository->searchTour($search),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('tour/show_all.html.twig', [
            'tours' => $tours,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{tour}", name="one_tour")
     * @param Tour $tour
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function showOne(Tour $tour, Request $request, EntityManagerInterface $em): Response
    {
        $booking = new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $booking->setUser($user);
            $booking->setTour($tour);
            $em->persist($booking);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre réservation a été prise en compte !'
            );

            return $this->redirectToRoute('one_tour',  ['tour' => $tour->getId()]);
        }

        return $this->render('tour/show_one.html.twig', [
            'tour' => $tour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin", name="tour_index", methods={"GET"})
     */
    public function index(TourRepository $tourRepository): Response
    {
        return $this->render('tour/index.html.twig', [
            'tours' => $tourRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="tour_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tour = new Tour();
        $form = $this->createForm(TourType::class, $tour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tour);
            $entityManager->flush();

            return $this->redirectToRoute('tour_index');
        }

        return $this->render('tour/new.html.twig', [
            'tour' => $tour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="tour_show", methods={"GET"})
     */
    public function show(Tour $tour): Response
    {
        return $this->render('tour/show.html.twig', [
            'tour' => $tour,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="tour_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tour $tour): Response
    {
        $form = $this->createForm(TourType::class, $tour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tour_index');
        }

        return $this->render('tour/edit.html.twig', [
            'tour' => $tour,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="tour_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tour $tour): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tour->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tour);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tour_index');
    }
}
