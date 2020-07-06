<?php

namespace App\Controller;

use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * 
     * @param AdRepository $adRepo
     * @param UserRepository $userRepository
     * @return Response
     */
    public function home(AdRepository $adRepo, UserRepository $userRepository)
    {
        return $this->render(
            'home2.html.twig',
            [
                'controller_name' => 'HomeController',
                'ads' => $adRepo->findBestAds(3),
                'users' => $userRepository->findBestUsers(2)
            ]
        );
    }
}
