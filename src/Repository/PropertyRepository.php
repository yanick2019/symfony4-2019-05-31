<?php

// src/Repository/PropertyRepository
# 数据库中表 Property  

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;


/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

   
    /**
     * @return Property[]
     */
    public function findAllVisible(): array
    {
        return $this->createQueryBuilder('p') ## 定义表property缩写为 p
           ->where('p.sold = 0 ') #where #flase = 0 
           // ->andWhere('p.exampleField = :val') #where 
            //->setParameter('val', $value) 
          //  ->orderBy('p.id', 'ASC')
            ->setMaxResults(10) # limit 
            ->getQuery()
            ->getResult()
        ;
    }

     /**
     * @return Property[];
     */
    public function findLatest(): array
    {
        /*
        return
        $this->createQueryBuilder('p') 
            ->where(  'p.sold = 0 ' ) 
            ->setMaxResults(4)   
            ->getQuery()
            ->getResult()
            */
        return
            $this->findVisibleQuery() 
                 ->setMaxResults(4)   
                ->getQuery()
                ->getResult()    
     ; 
    }

    # 加 ":QueryBuilder" 要先载入use Doctrine\ORM\QueryBuilder; 否则报错must be an instance of App\Repository\QueryBuilder, instance of Doctrine\ORM\QueryBuilder returned
    private function findVisibleQuery(  $sqlWhere = ' p.sold = false  ' ) : QueryBuilder 
    {
        return $this->createQueryBuilder('p') 
        ->where(  $sqlWhere   )
        ;
            
    }

    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
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
