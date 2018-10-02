<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Products::class);
    }

    /**
     * @return Products[] Returns an array of Products objects
     * @param $parameters
     */
    public function findByParameters(array $parameters)
    {

        $type = (isset($parameters['order'][0]) && $parameters['order'][0] !== '') ? $parameters['order'][0] : 'p.id';
        $order = (isset($parameters['order'][1]) && $parameters['order'][1] == 'DESC') ? 'DESC' : 'ASC';

        $qb = $this->createQueryBuilder('p');
        if(isset($parameters['dateRange']) && is_array($parameters['dateRange'])) {
            $qb->andWhere('p.created_at > :min')->setParameter('min', new \DateTime($parameters['dateRange'][0]));
            $qb->andWhere('p.created_at < :max')->setParameter('max', new \DateTime($parameters['dateRange'][1]));
        }
        if(isset($parameters['priceRange']) && is_array($parameters['priceRange'])) {
            $qb->andWhere('p.price > :price_min')->setParameter('price_min', $parameters['priceRange'][0]);
            $qb->andWhere('p.price < :price_max')->setParameter('price_max', $parameters['priceRange'][1]);
        }
        if(isset($parameters['count']) && is_int($parameters['count']) && $parameters['count'] != 0)
            $qb->setFirstResult(0)->setMaxResults($parameters['count']);
        if($type == 'name'){
            $qb->orderBy('p.name', $order);
        }elseif($type == 'price'){
            $qb->orderBy('p.price', $order);
        }elseif($type == 'date'){
            $qb->orderBy('p.created_at', $order);
        }else{
            $qb->orderBy('p.id', $order);
        }

        return $qb->getQuery()->execute();
    }


    /*
    public function findOneBySomeField($value): ?Products
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
