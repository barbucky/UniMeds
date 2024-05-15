<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_of_appointment = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $start_hour = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfAppointment(): ?\DateTimeInterface
    {
        return $this->date_of_appointment;
    }

    public function setDateOfAppointment(\DateTimeInterface $date_of_appointment): static
    {
        $this->date_of_appointment = $date_of_appointment;

        return $this;
    }

    public function getStartHour(): ?\DateTimeInterface
    {
        return $this->start_hour;
    }

    public function setStartHour(\DateTimeInterface $start_hour): static
    {
        $this->start_hour = $start_hour;

        return $this;
    }


    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
