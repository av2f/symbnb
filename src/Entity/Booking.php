<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Ajout des fonctions prePersist et getDuration à la fin
// la fonction prePersist est liée à HasLifecycleCallbacks

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(
     * message="Attention la date doit être au bon format"
     * )
     * @Assert\GreaterThan(
     * "today",
     * message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui",
     * groups={"front"}
     * )
     * Groups permet de définir pour quel validation_groups défini dans le controller cette
     * règle s'applique
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(
     * message="Attention la date doit être au bon format"
     * )
     * @Assert\GreaterThan(
     * propertyPath="startDate",
     * message="La date d'arrivée doit être supérieure à la date de départ"
     * )
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * Callback à chaque fois que l'on créé une réservation
     * 
     * @ORM\prePersist()
     * @ORM\preUpdate()
     *
     * @return void
     */
    public function prePersist() {
        if (empty($this->createdAt)){
            $this->createdAt=new \DateTime();
        }

        if(empty($this->amount)) {
            // Prix de l'annonce * nombre de nuit
            $this->amount=$this->ad->getPrice() * $this->getDuration();
        }
    }
    public function isBookableDates(){
        // 1) Il faut connaitre les date qui sont impossibles pour l'annonce
        $notAvailableDays=$this->ad->getNotAvailableDays();
        // 2) Il faut comparer les dates choisies avec les dates impossibles
        $bookingDays=$this->getDays();

        // fonction anonyme
        $formatDay=function($day){
            return $day->format('Y-m-d'); 
         };
        
        // tableau des chaines de caractères de mes journées
        $days=array_map($formatDay, $bookingDays);

        $notAvailable=array_map($formatDay, $notAvailableDays);

         foreach($days as $day){
             if(array_search($day, $notAvailable) !== false) return false;
         }

         return true;
    }

    /**
     * Cette fonction va permettre de récupérer un tableau des journées qui correspondent à ma réservation
     *
     * @return array un tableau d'objets DateTime représentant les jours de la réservation
     */
    public function getDays(){
        $resultat=range(
            $this->startDate->getTimestamp(),
            $this->endDate->getTimestamp(),
            24*60*60
        );
        // voir explications de array_map & array_merge dans Ad.php
        $days=array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getDuration() {
        $diff=$this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
