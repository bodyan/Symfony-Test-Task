<?php

namespace App\Service;

use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;

class ProductsService
{

    /** @var  EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handleParameters($parameters = null) :array
    {
        /** @var $productsRepository ProductsRepository*/
        $productsRepository = $this->entityManager->getRepository(Products::class);

        $result = $productsRepository->findByParameters($parameters);
        return $result;
    }
}
