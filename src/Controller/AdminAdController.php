<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Repository\AdRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * requirement <> le ? indique optionnel et par defaut page 1
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index(AdRepository $repo, $page)
    {
        // Méthode find : permet de retrouver un enregistrement par son identifiant
        // $ad->$repo->find(132)
        /* $ad->$repo->findOneBy([
            'title'=>"Annonce",
            'id'=>233
        ]);*/

        /* 
        Pour la pagination : on prend les 5 premières annonces à partir de 0
        $ad->$repo->findBy([],[],5,0) */
        
        $limit=10;
        $start=$page*$limit-$limit;
        $total=count($repo->findAll());
        $pages=ceil($total/$limit); // ceil=arrondi au-dessus

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findBy([],[],$limit,$start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

   /**
    * Permet de supprimer une annonce
    * 
    * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
    *
    * @param Ad $ad
    * @param ObjectManager $manager
    * @return Response
    */
    public function delete(Ad $ad, ObjectManager $manager){

        if(count($ad->getBookings())>0){
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_ads_index');
    }
}
