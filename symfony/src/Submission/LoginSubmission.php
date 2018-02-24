<?php

namespace App\Submission;

class LoginSubmission
{
    private $memberId;

    private $memberPassword;

    public function __construct(
        ?string $memberId = null, 
        ?string $memberPassword = null)
    {
        $this->memberId = $memberId;
        $this->memberPassword = $memberPassword;
    }

    public function getMemberId(): ?string
    {
        return $this->memberId;
    }

    public function getMemberPassword(): ?string
    {
        return $this->memberPassword;
    }

    public function setMemberId(?string $memberId): void
    {
        $this->memberId = $memberId;
    }

    public function setMemberPassword(?string $memberPassword): void
    {
        $this->memberPassword = $memberPassword;
    }
}
