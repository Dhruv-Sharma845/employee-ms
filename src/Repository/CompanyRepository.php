<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Company::class);
    }

//    /**
//     * @return Company[] Returns an array of Company objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Company
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function transform(Company $company) {
        
        $employeesArray = [];
        if($company->getEmployees()) {
            foreach($company->getEmployees() as $employee) {
                $employeesArray[] = [
                    'id' => (int) $employee->getId(),
                    'firstName' => (string) $employee->getFirstName(),
                    'lastName' => (string) $employee->getLastName(),
                    'phone' => (string) $employee->getPhone()
                ];
            }
        }
        return [
            'id' => (int) $company->getId(),
            'name' => (string) $company->getName(),
            'phone' => (string) $company->getPhone(),
            'address' => (string) $company->getAddress(),
            'employees' => (array) $employeesArray
        ];
    }

    public function transformAll()
    {
        $companies = $this->findAll();
        $companiesArray = [];

        foreach ($companies as $company) {
            $companiesArray[] = $this->transform($company);
        }

        return $companiesArray;
    }
}
