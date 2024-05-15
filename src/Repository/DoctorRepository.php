<?php

namespace App\Repository;

use App\Entity\Doctor;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;


/**
 * @extends ServiceEntityRepository<Doctor>
 *
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[]    findAll()
 * @method Doctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    public function getRandomDoctors (int $nombreDocs):array
    {
        $queryBuilder = $this->createQueryBuilder('d');
        return $queryBuilder
            ->setMaxResults($nombreDocs)
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult();

    }

    /**
     * Trouve un docteur en résultat de recherche dans le prénom, le nom ou la spécialisation du praticien
     */
     public function findBySearch(SearchData $searchData)
    {
        $doctors = $this->createQueryBuilder('p');
            #->addOrderBy('p.','ASC');
        if(!empty($searchData)){

            $doctors = $doctors
                ->join('p.user','user')
                ->where('p.specialization LIKE :q')
                ->orWhere('user.first_name LIKE :q')
                ->orWhere('user.last_name LIKE :q')
                ->orWhere('user.fullName LIKE :q')
                ->setParameter('q',"%{$searchData}%");
        }


        return $doctors
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Doctor[] Returns an array of Doctor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Doctor
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
