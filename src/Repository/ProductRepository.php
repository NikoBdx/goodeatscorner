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
    * recherche de produit par le nom (utilisé pour la barre de recherche)
    */
   public function findProductsByNameSearch(SearchData $searchData)
    {
        $data = $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC');

        if (!empty($searchData->q)) {
            $data = $data
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        $data = $data
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * retourne un nombre voulu des derniers produits créés par date
     */
    public function findLastProducts($number)
    {
        return $this->createQueryBuilder('p')
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
            ->addOrderBy('p.price', 'ASC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

}
