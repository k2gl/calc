<?php

namespace App\Repository;

use App\Entity\Coupon;
use App\Entity\TaxSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxSystem>
 *
 * @method TaxSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxSystem[]    findAll()
 * @method TaxSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxSystem::class);
    }

    public function findOneByCountryCode(string $countryCode): ?TaxSystem
    {
        return $this->findOneBy(['countryCode' => $countryCode]);
    }
}
