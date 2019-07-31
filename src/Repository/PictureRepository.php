<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Picture::class);
    }


    public function findPropertyId(int $propertyId) 
    {
        return $this->createQueryBuilder('p')
            ->andWhere(' p.property_id = :val ')
            ->setParameter('val', $propertyId)
            ->getQuery()
            ->getResult();
    }
    /**
     * @param Property[] $properties
     * @return ArrayCollection
     */
    public function findPicForProperty( array $properties  ) 
    {
        $qb = $this->createQueryBuilder('p');
        $pictures = $qb
            ->select('p')
            ->where(
                $qb->expr()->in(
                    'p.id',
                    $this->createQueryBuilder('p2')
                        ->select('Max(p2.id)')
                        ->where('p2.Property IN (:properties)')
                        ->groupBy('p2.Property')
                        ->getDQL()
                )

            )
            ->getQuery()
            ->setParameter('properties', $properties)
            ->getResult();
         
        $picturesArr = [] ;
        if( count($pictures) > 0 )
        {
            $picturesArr = 
            array_reduce($pictures , function( $arr , Picture $picture)
            {
                    $arr[ $picture->getProperty()->getId() ] = $picture ;

                    return $arr ; 

            }   ) ;
        }    
        
        
         
         return new ArrayCollection( $picturesArr ) ;

    }


    // /**
    //  * @return Picture[] Returns an array of Picture objects
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
    public function findOneBySomeField($value): ?Picture
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
