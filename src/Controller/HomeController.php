<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdRepository;
use App\Repository\UserRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(AdRepository $adRepo, UserRepository $userRepo)
    {
        return $this->render('home/index.html.twig', [
            'ads'=>$adRepo->findBestAds(3),
            'users'=>$userRepo->findBestUsers(2)
        ]);
    }
}
