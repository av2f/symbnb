<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

class AdminBookingController extends AbstractController
{
    /**
     * 
     * Affiche la liste des réservations
     * 
     * @Route("/admin/bookings", name="admin_bookings_index")
     * 
     * @param BookingRepository $repo
     */
    public function index(BookingRepository $repo)
    {
        $bookings=$repo->findAll();

        return $this->render('admin/booking/index.html.twig', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Formulaire d'édition de réservation
     * 
     * @Route("/admin/bookings/{id}/edit", name="admin_bookings_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking, Request $request, ObjectManager $manager){

        $form=$this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            // on met à 0 pour que la fonction prePersist() dans l'entity booking
            // recalcule le montant
            // on a ajouté aussi @ORM\preUpdate pour prendre en compte avant une mise à jour
            $booking->setAmount(0);

            // pour une mise à jour, pas obligé de mettre le persist car existe déjà
            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success',
                "La réservation N°{$booking->getId()} a bien été modifiée..."
            );
            
            return $this->redirectToRoute('admin_bookings_index');
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**
    * Permet de supprimer une réservation
    * 
    * @Route("/admin/bookings/{id}/delete", name="admin_bookings_delete")
    *
    * @param Booking $booking
    * @param ObjectManager $manager
    * @return Response
    */
    public function delete(Booking $booking, ObjectManager $manager){

        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée !"
        );

        return $this->redirectToRoute('admin_bookings_index');
    }
}
