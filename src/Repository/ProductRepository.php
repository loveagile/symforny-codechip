<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */

    public function searchProducts(string $searchQuery)
    {
        return $this->createQueryBuilder('p') // seletct * from products as p
            ->andWhere('p.name LIKE :search')
            ->setParameter('search', '%' . $searchQuery . '%')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProductToCartBySlug(string $slug)
    {
        return $this->createQueryBuilder('p') // seletct * from products as p
        ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->select('p.name', 'p.price', 'p.slug')
            ->getQuery()
            ->getOneOrNullResult();
    }
    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
