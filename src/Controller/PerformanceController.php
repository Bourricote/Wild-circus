<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Performance;
use App\Entity\PerformanceSearch;
use App\Form\CommentType;
use App\Form\PerformanceSearchType;
use App\Form\PerformanceType;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * @Route("/performance")
 */
class PerformanceController extends AbstractController
{
    /**
     * @Route("/", name="all_performances")
     * @param PerformanceRepository $performanceRepository
     * @return Response
     */
    public function showAll(PerformanceRepository $performanceRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $search = new PerformanceSearch();

        $form = $this->createForm(PerformanceSearchType::class, $search);
        $form->handleRequest($request);

        $performances = $paginator->paginate(
            $performanceRepository->searchPerformance($search),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('performance/show_all.html.twig', [
            'performances' => $performances,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{performance}", name="one_performance")
     * @param Performance $performance
     * @param Request $request
     * @return Response
     */
    public function showOne(Performance $performance, Request $request, EntityManagerInterface $em): Response
    {
        $comments = $performance->getComments();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $author = $this->getUser();
            $comment->setPerformance($performance);
            $comment->setAuthor($author);
            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                'Merci pour votre commentaire !'
            );

            return $this->redirectToRoute('one_performance',  ['performance' => $performance->getId()]);
        }

        return $this->render('performance/show_one.html.twig', [
            'performance' => $performance,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin", name="performance_index", methods={"GET"})
     */
    public function index(PerformanceRepository $performanceRepository): Response
    {
        return $this->render('performance/index.html.twig', [
            'performances' => $performanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="performance_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $performance = new Performance();
        $form = $this->createForm(PerformanceType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($performance);
            $entityManager->flush();

            $mailBody = file_get_contents('../templates/email/new_performance.html.twig');

            $email_vars = [
                'name' => $form['name']->getData(),
                'path' => 'http://localhost:8000/performance/' . $performance->getId(),
            ];

            if(isset($email_vars)){
                foreach($email_vars as $k=>$v){
                    $mailBody = str_replace($k, $v, $mailBody);
                }
            }

            $mail = new PHPMailer(true);
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $this->getParameter('mail_server');
            $mail->SMTPAuth = true;
            $mail->Username = $this->getParameter('mail_from');
            $mail->Password = $this->getParameter('mail_password');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom($this->getParameter('mail_from'));
            $mail->addAddress($this->getParameter('mail_from'));
            $mail->isHTML(true);
            $mail->Subject = utf8_decode('Un nouveau numéro : "' . $form['name']->getData() . '" !');
            $mail->Body = utf8_decode(nl2br($mailBody));

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            if(!$mail->send()) {
                echo 'Erreur, message non envoyé.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'Le message a bien été envoyé !';
            }

            $this->addFlash(
                'success',
                'Numéro ajouté !'
            );

            return $this->redirectToRoute('performance_index');
        }

        return $this->render('performance/new.html.twig', [
            'performance' => $performance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="performance_show", methods={"GET"})
     */
    public function show(Performance $performance): Response
    {
        return $this->render('performance/show.html.twig', [
            'performance' => $performance,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="performance_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Performance $performance): Response
    {
        $form = $this->createForm(PerformanceType::class, $performance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('performance_index');
        }

        $this->addFlash(
            'success',
            'Numéro modifié !'
        );

        return $this->render('performance/edit.html.twig', [
            'performance' => $performance,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="performance_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Performance $performance): Response
    {
        if ($this->isCsrfTokenValid('delete'.$performance->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($performance);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Numéro supprimé !'
            );
        }

        return $this->redirectToRoute('performance_index');
    }
}
