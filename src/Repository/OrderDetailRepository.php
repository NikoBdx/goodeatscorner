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

    public function findOneBySomeField($value): ?OrderDetail
    {
        return $this->createQueryBuilder('od')
            ->andWhere('od.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * retourne toutes les commandes avec leurs détails par utilsateur
     */
    public function findOrdersByUser($user)
    {
        return  $this->createQueryBuilder('od')
        ->select('o', 'od')
        ->join('od.order_related', 'o')
        ->andWhere('o.user = :val')
        ->setParameter('val', $user)
        ->getQuery()
        ->getResult();
    }

    /**
     * retourne toutes les détails pour une commande
     */
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
