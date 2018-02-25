<?php

namespace App\Repository;

use App\Entity\U2fToken;
use App\Repository\MemberRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Firehed\U2F\Registration;
use Symfony\Bridge\Doctrine\RegistryInterface;

class U2fTokenRepository extends ServiceEntityRepository
{
    private $mr;

    public function __construct(
        MemberRepository $mr,
        RegistryInterface $registry)
    {
        parent::__construct($registry, U2fToken::class);
        $this->mr = $mr;
    }

    /**
     * @todo Make it only generate one query.
     */
    public function getMemberRegistrations(string $username): array
    {
        $qb = $this->createQueryBuilder('u');
        $memberId = $this->mr->getMemberId($username);
        $u2fTkns = $qb
            ->where('u.member = :member_id')
            ->setParameter('member_id', $memberId )
            ->getQuery()
            ->getResult()
        ;
        $u2fRegistrations = [];
        foreach ($u2fTkns as $u2fTkn) {
            $u2fReg = new Registration();
            $u2fReg->setCounter($u2fTkn->getCounter());
            $u2fReg->setAttestationCertificate($u2fTkn->getAttestation());
            $u2fReg->setPublicKey(base64_decode($u2fTkn->getPublicKey()));
            $u2fReg->setKeyHandle(base64_decode($u2fTkn->getKeyHandle()));
            $u2fRegistrations[$u2fTkn->getId()] = $u2fReg;
        }

        return $u2fRegistrations;
    }
}
