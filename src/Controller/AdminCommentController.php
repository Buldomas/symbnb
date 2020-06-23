<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Service\PaginationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comments/{page<\d+>?1}", name="admin_comment_index")
     */
    public function index(CommentRepository $repo, $page, PaginationService $pagination)
    {
        $pagination->setEntityClass(Comment::class)
            ->setPage($page)
            ->setLimit(5);

        return $this->render('admin/comment/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     *
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment, Request $request, ManagerRegistry $managerRegistry)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        /* Si soumis et valide */
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();

            $manager->persist($comment);
            $manager->flush();

            /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
            $this->addFlash(
                'success',
                "Les modifications ont bien été enregistrées !"
            );
            return $this->redirectToRoute('admin_comment_index');
        }
        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ManagerRegistry $managerRegistry
     * @return Response
     */
    public function delete(Comment $comment,  ManagerRegistry $managerRegistry)
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($comment);
        $manager->flush();
        /* success peut être changé mais ici il correspond aux couleurs du Bootstrap */
        $this->addFlash(
            'success',
            "Le commentaire a bien été supprimé !"
        );
        return $this->redirectToRoute('admin_comment_index');
    }
}
