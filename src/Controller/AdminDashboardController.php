<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\StatsService;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, StatsService $statsService)
    {
        $stats=$statsService->getStats();

        $bestAds=$statsService->getAdsStats('DESC');

        $worstAds=$statsService->getAdsStats('ASC');

        // faire dump($bestAds) pour voir le résulta

        return $this->render('admin/dashboard/index.html.twig', [
            // la fonction compact permet de créer un tableau avec comme clé le même nom que la valeur
            // par exemple 'users'=>$users
            'stats' => $stats,
            'bestAds'=>$bestAds,
            'worstAds'=>$worstAds
        ]);
    }
}
