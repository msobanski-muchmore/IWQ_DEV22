<?php


namespace App\Service;

use App\Domain\Employee;
use Doctrine\ORM\EntityManager;

final class EmployeeService
{

    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function createEmployee(string $firstName, string $lastName, string $address, string $pesel, string &$error = ''): ?Employee
    {

        $newEmployee = new Employee();
        $newEmployee->setFirstName($firstName);
        $newEmployee->setLastName($lastName);
        $newEmployee->setAddress($address);
        $newEmployee->setPesel($pesel);

        $this->em->persist($newEmployee);
        $this->em->flush();

        return $newEmployee;
    }

    /**
     * @return Employee[]
     */
    public function getEmployees(): array {
        return $this->em->getRepository(Employee::class)->findAll();
    }

    /**
     * @param int $id
     * @return Employee|null
     */
    public function getOneEmployee(int $id): ?Employee {
        return $this->em->getRepository(Employee::class)->find($id);
    }

    public function saveOneEmployee(Employee $employee) : Employee{
        $this->em->persist($employee);
        $this->em->flush();
        return  $employee;
    }

    public function deleteEmployee(Employee $employee){
        $this->em->remove($employee);
        $this->em->flush();
    }
}
