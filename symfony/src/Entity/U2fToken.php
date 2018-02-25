<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\U2fTokenRepository")
 */
class U2fToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=999)
     */
    private $attestation;

    /**
     * @ORM\Column(type="integer")
     */
    private $counter;

    /**
     * @ORM\Column(type="string", length=88)
     */
    private $keyHandle;

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     */
    private $member;

    /**
     * @ORM\Column(type="string", length=88, unique=true)
     */
    private $publicKey;

    public function __construct(
        ?int $id,
        string $attestation,
        int $counter,
        string $keyHandle,
        Member $member,
        string $publicKey)
    {
        $this->id = $id;
        $this->attestation = $attestation;
        $this->counter = $counter;
        $this->keyHandle = $keyHandle;
        $this->member = $member;
        $this->publicKey = $publicKey;
    }

    public function getAttestation(): string
    {
        return $this->attestation;
    }

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeyHandle(): string
    {
        return $this->keyHandle;
    }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }
}
