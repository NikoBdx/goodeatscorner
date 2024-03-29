<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Model\SearchData;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * retourne tous les produits d'une famille
     */

   public function findProductsByFamily($value)
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.family = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getArrayResult()
       ;
   }

   /**
    * retourne tous les produits d'un rayon
    */

   public function findProductsByShelf($value)
   {
       return $this->createQueryBuilder('p')
           ->select('p','f', 's')
           ->join('p.family', 'f')
           ->join('f.shelf', 's')
           ->andWhere('s.id = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getArrayResult()
       ;
   }

/**
 *
 *
 */
   public function findProductsByNameSearch(SearchData $searchData)
    {
        $qb = $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC');

        if (!empty($searchData->q)) {
            $qb ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        return $qb->getQuery()
                ->getResult();
    }

    /**
     * retourne un nombre voulu des derniers produits créés par date
     */
    public function findLastProducts($number)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock > 0')
            ->addOrderBy('p.createdAt', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * retourne un nombre voulu des produits les moins chers
     */
    public function findLessExpensiveProducts($number)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock > 0')
            ->addOrderBy('p.price', 'ASC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

}
