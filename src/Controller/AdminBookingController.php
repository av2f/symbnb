<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\AdminBookingType;

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
     * @Route("/admin/booking/{id}/edit", "name="admin_bookings_edit")
     *
     * @return Response
     */
    public function edit(Booking $booking){

        $form=$this->createForm(AdminBookingType::class, $booking);

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }
}
