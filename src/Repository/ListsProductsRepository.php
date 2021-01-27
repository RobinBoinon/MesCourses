<?php

namespace App\Repository;

use App\Entity\ListsProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListsProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListsProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListsProducts[]    findAll()
 * @method ListsProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListsProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListsProducts::class);
    }

    // /**
    //  * @return ListsProducts[] Returns a ListsProducts objects
    //  */
    // public function findOneByCoupleId($lists_id,$product_id)
    // {
    //     $conn = $this->getEntityManager()
    //         ->getConnection();
    //     $sql = 'SELECT * FROM lists_products WHERE `store_id` = '.$lists_id.' AND `product_id` = '.$product_id;
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute();
    //     $temp = $stmt->fetchAll();
    //     if(empty($temp)){
    //       return null;
    //     }
    //     else{
    //       return $temp;
    //     }
    // }
    public function findOneByCoupleId($lists_id,$product_id)
    {
      return $this->createQueryBuilder('l')
          ->andWhere('l.lists  = :val1')
          ->setParameter('val1', $lists_id)
          ->andWhere('l.product  = :val2')
          ->setParameter('val2', $product_id)
          ->getQuery()
          ->getOneOrNullResult()
      ;
    }
}
