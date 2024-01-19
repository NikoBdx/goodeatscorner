<?php

namespace App\Repository;

use App\Entity\OrderDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderDetail>
 *
 * @method OrderDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderDetail[]    findAll()
 * @method OrderDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderDetail::class);
    }

//    /**
//     * @return OrderDetail[] Returns an array of OrderDetail objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneBySomeField($value): ?OrderDetail
   {
       return $this->createQueryBuilder('od')
           ->andWhere('od.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   public function findOrdersByUser($user)
   {
        return  $this->createQueryBuilder('od')
        ->select('o', 'od')
        ->join('od.order_related', 'o')
        ->andWhere('o.user = :val')
        ->setParameter('val', $user)
        ->getQuery()
        ->getResult();

    //     $this->createQueryBuilder('pr')
    // ->select('partial pk.{id, name}, partial pr.{id, label}')
    // ->join('pr.package', 'pk')
    // ->getQuery()
    // ->getArrayResult()

   }

   public function findDetailsByOrder($order)
   {
        return $this->createQueryBuilder('od')
            ->select('p', 'od')
            ->join('od.order_related', 'o')
            ->join('od.product', 'p')
            ->andWhere('o.id = :val')
            ->setParameter('val', $order)
            ->getQuery()
            ->getArrayResult();
    }

}
