<?php

namespace App\Controller;

use App\Repository\PerformanceRepository;
use App\Repository\TourRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PerformanceRepository $performanceRepository, TourRepository $tourRepository)
    {
        $performances = $performanceRepository->findBy([], ['createdAt' => 'DESC'], 3, 0 );

        $nextTour = $tourRepository->findBy([], ['date' => 'ASC'], 1, 0);

        return $this->render('home.html.twig', [
            'performances' => $performances,
            'next_tour' => $nextTour,
        ]);
    }

    /**
     * @Route("/game", name="game", methods={"GET","POST"})
     */
    public function game()
    {
        return $this->render('game.html.twig');
    }

    /**
     * @Route("/win", name="win", methods={"GET","POST"})
     */
    public function win()
    {
        $user = $this->getUser();

        $mailBody = file_get_contents('../templates/email/winner.html.twig');

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
        $mail->addAddress($user->getEmail());

        $mail->isHTML(true);
        $mail->Subject = utf8_decode('Votre place gratuite pour Avatar Circus !');
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
            'Bien joué !'
        );
        return $this->redirectToRoute('home');
    }
}
