<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ManagerRegistry $managerRegistry, StatsService $statsService)
    {
        //$manager  = $managerRegistry->getManager();
        $stats    = $statsService->getStats();
        $bestAds  = $statsService->getAdsStats('DESC');
        $worstAds = $statsService->getAdsStats('ASC');
        return $this->render('admin/dashboard/index.html.twig', [
            'Stats' => $stats,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds,
        ]);
    }
}
