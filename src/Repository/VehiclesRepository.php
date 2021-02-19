<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiclesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    // /**
    //  * @return Vehicle[] Returns an array of Vehicle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param $params
     * @return Vehicle[] Returns an array of Vehicle objects
     */
    public function findByParams($params): array
    {
        $queryParams  = null;
        $queryBuilder = $this->createQueryBuilder('v')
            ->where('1=1');
        if (isset($params['brand'])) {
            $queryBuilder->andWhere('v.brand = :brand');
            $queryParams['brand'] = (int)$params['brand'];
        }
        if (isset($params['isNew'])) {
            $queryBuilder->andWhere('v.isNew = :isNew');
            $queryParams['isNew'] = (bool)$params['isNew'];
        }
        if (isset($params['maxModelYear'])) {
            $queryBuilder->andWhere('v.modelYear <= :maxModelYear');
            $queryParams['maxModelYear'] = $params['maxModelYear'];
        }
        if (isset($params['minModelYear'])) {
            $queryBuilder->andWhere('v.modelYear >= :minModelYear');
            $queryParams['minModelYear'] = $params['minModelYear'];
        }
        if (isset($params['maxPrice'])) {
            $queryBuilder->andWhere('v.price <= :maxPrice');
            $queryParams['maxPrice'] = $params['maxPrice'];
        }
        if (isset($params['minPrice'])) {
            $queryBuilder->andWhere('v.price >= :minPrice');
            $queryParams['minPrice'] = $params['minPrice'];
        }
        if (isset($params['hasRainSensor'])) {
            $queryBuilder->andWhere('v.hasRainSensor = :hasRainSensor');
            $queryParams['hasRainSensor'] = (bool)$params['hasRainSensor'];
        }
        if ($queryParams) {
            $queryBuilder->setParameters($queryParams);
        }
        return $queryBuilder->getQuery()->getResult();
    }


}
