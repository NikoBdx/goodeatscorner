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

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneBySomeField($value): ?Product
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }

   public function findProductsByFamily($value)
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.family = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getArrayResult()
       ;
   }

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

}
