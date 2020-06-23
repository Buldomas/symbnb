<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     * La variable page demande un nombre (\d+) et optionnel (?) avec 1 par défaut
     */
    public function index(AdRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Ad::class)
            ->setPage($page)
            ->setLimit(10);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, ManagerRegistry $managerRegistry)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        /* Si soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();

            /*foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }*/
            $manager->persist($ad);
            $manager->flush();

            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'success',
                "Les modifications de <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );
        }
        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param Ad $ad
     * @param ManagerRegistry $managerRegistry
     * @return Response
     */
    public function delete(Ad $ad,  ManagerRegistry $managerRegistry)
    {
        // s'il y a des réservations, on ne peut pas supprimer
        if (count($ad->getBookings()) > 0) {
            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'warning',
                "L'annonce <strong>{$ad->getTitle()}</strong> a déjà des réservations !"
            );
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($ad);
            $manager->flush();
            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
