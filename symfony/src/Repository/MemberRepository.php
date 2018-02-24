<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberRepository extends ServiceEntityRepository
{
    private $encoder;

    public function __construct(
        RegistryInterface $registry,
        UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($registry, Member::class);
        $this->encoder = $encoder;
    }

    public function save(Member $member, string $password)
    {
        $member->setPassword($this
            ->encoder
            ->encodePassword($member, $password)
        );
        $em = $this->getEntityManager();
        $em->persist($member);
        $em->flush();
    }
}
