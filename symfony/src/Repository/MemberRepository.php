<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MemberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Member::class);
    }

    public function getMemberId(string $username): int
    {
        return $this
            ->createQueryBuilder('m')
            ->select('m.id')
            ->where('m.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
