<?php

namespace App\Entity;

use App\Form\PatientType;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $email = null;

    #[ORM\Column(length: 10)]
    #[Assert\Regex('/^[0-9]+$/',message: "Ce champ ne peut contenir que des chiffres")]
    private ?string $phone = null;

    #[ORM\Column(length: 30)]
    private array $roles = [];

    #[ORM\Column(length: 64)]
    /*
    * Regex pour les conditions que j'ai imposées pour le mot de passe:
    * mini 1 Maj
    * mini 12 caractères
    *
    * */
    /*#[Assert\Regex(
        '/^(?=.*[!@#$%^&*-_])(?=.*[0-9])(?=.*[A-Z]).{12,}$/',
        message: "Le mot de passe doit être composé d'au moins 12 caractères et contenir au moins: 1 majuscule, 1 chiffre et un caractère spécial")]
    */
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;


    #[ORM\Column(length: 80)]
    #[Assert\Length(min: 2,minMessage: 'Ce champ doit contenir au moins 2 caractères')]
    #[Assert\Regex(
        '/^[a-zA-Zéè\'-]+$/',
        message: "Le prénom ne peut contenir que des lettres et les caractères \" ' ou -\" ")]
    protected ?string $first_name = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2,minMessage: 'Ce champ doit contenir au moins 2 caractères')]
    #[Assert\Regex(
        '/^[a-zA-Z\'-]+$/',
        message: "Le nom de famille ne peut contenir que des lettres et les caractères \" ' ou -\" ")]
    protected ?string $last_name = null;

    #[ORM\ManyToOne(targetEntity: Civility::class,inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(type: Civility::class)]
    #[Assert\Valid]
    protected?Civility $civility = null;

    #[ORM\ManyToOne(targetEntity:Address::class,inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Type(type: Address::class)]
    #[Assert\Valid]
    protected ?Address $address = null;

    #[ORM\OneToOne(targetEntity: Patient::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    #[Assert\Type(type: Patient::class)]
    #[Assert\Valid]
    protected ?Patient $patient = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    protected ?Doctor $doctor = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_Of_Birth = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fullName = null;

    public function getFullName(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): static
    {
        $this->civility = $civility;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(Patient $patient): static
    {
        // set the owning side of the relation if necessary
        if ($patient->getUser() !== $this) {
            $patient->setUser($this);
        }

        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(Doctor $doctor): static
    {
        // set the owning side of the relation if necessary
        if ($doctor->getUser() !== $this) {
            $doctor->setUser($this);
        }

        $this->doctor = $doctor;

        return $this;
    }
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    #[ORM\PrePersist]
    public function setCreateAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_Of_Birth;
    }

    public function setDateOfBirth(\DateTimeInterface $date_Of_Birth): static
    {
        $this->date_Of_Birth = $date_Of_Birth;

        return $this;
    }

    public function setFullName(?string $fullName): static
    {
        $this->fullName = $fullName;

        return $this;
    }




}