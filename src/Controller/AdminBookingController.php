<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_bookings_index")
     */
    public function index(BookingRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Booking::class)
            ->setPage($page)
            ->setLimit(4);

        return $this->render('admin/booking/index.html.twig', [
            'pagination'  => $pagination,
        ]);
    }
    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     *
     * @param Booking $booking
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ManagerRegistry $managerRegistry)
    {
        $form = $this->createForm(AdminBookingType::class, $booking);
        $form->handleRequest($request);

        /* Si soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            $booking->setAmount(0);
            $manager = $managerRegistry->getManager();

            $manager->persist($booking); // pas obligatoire mais précaution
            $manager->flush();

            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'success',
                "La réservation N° {$booking->getId()} a bien été modifiée !"
            );
            return $this->redirectToRoute('admin_bookings_index');
        }
        return $this->render('admin/booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     *
     * @param Booking $comment
     * @param ManagerRegistry $managerRegistry
     * @return Response
     */
    public function delete(Booking $booking,  ManagerRegistry $managerRegistry)
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($booking);
        $manager->flush();
        /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
        $this->addFlash(
            'success',
            "La réservation a bien été supprimée !"
        );
        return $this->redirectToRoute('admin_bookings_index');
    }
}
