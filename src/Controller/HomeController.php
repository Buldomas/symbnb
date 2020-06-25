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
     */
    public function home(AdRepository $adRepo, UserRepository $userRepository)
    {
        $prenoms = ["Lior" => 31, "Joseph" => 12, "Anne" => 55];
        return $this->render(
            'home.html.twig',
            [
                'ads' => $adRepo->findBestAds(3),
                'users' => $userRepository->findBestUsers(2)
            ]
        );
    }
}
