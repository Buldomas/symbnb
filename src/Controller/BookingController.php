<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Comment;
use App\Form\BookingType;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    /**
     * @Route("/ads/{slug}/book", name="booking_create")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request, ManagerRegistry $managerRegistry)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $booking->setBooker($user)
                ->setAd($ad);

            // Si dates indisponibles
            if (!$booking->isBookableDates()) {
                $this->addFlash(
                    'warning',
                    "Les dates choisies ne peuvent être réservées. Déjà prises"
                );
            } else {
                $manager = $managerRegistry->getManager();
                $manager->persist($booking);
                $manager->flush();

                return $this->redirectToRoute("booking_show", [
                    'id' => $booking->getId(),
                    'withAlert' => true
                ]);
            }
        }

        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Affichage de la réservation
     *@Route("/booking/{id}", name="booking_show")
     * @param Booking $booking
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @return Response
     */
    public function show(Booking $booking, Request $request, ManagerRegistry $managerRegistry)
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAd($booking->getAd())
                ->setAuthor($this->getUser());
            $manager = $managerRegistry->getManager();
            $manager->persist($comment);
            $manager->flush();
            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'success',
                "Votre commentaire a bien été pris en compte !"
            );
        }

        return $this->render("booking/show.html.twig", [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }
}
