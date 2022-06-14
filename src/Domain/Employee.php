<?php

declare(strict_types=1);

namespace App\Domain;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeeRepository;

/**
 * @ORM\Entity(repositoryClass=EmployeeRepository::class)
 * @ORM\Table(name="employee")
 *
 */
class Employee
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     *
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=45, nullable=false, name="first_name")
     */
    private ?string $firstName  = null;

    /**
     * @ORM\Column(type="string", length=45, nullable=false, name="last_name")
     */
    private ?string $lastName = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="address")
     */
    private ?string $address = null;

    /**
     * @ORM\Column(type="string", length=11, nullable=false, name="pesel")
     */
    private ?string $pesel = null;

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    /**
     * @return string|null
     */
    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    /**
     * @param string $pesel
     */
    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}
