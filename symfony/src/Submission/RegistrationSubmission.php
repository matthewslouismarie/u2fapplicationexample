<?php

namespace App\Submission;

class RegistrationSubmission
{
    private $password;

    private $u2fResponse;

    private $username;

    public function __construct(
        ?string $password = null,
        ?string $u2fResponse = null,
        ?string $username = null)
    {
        $this->password = $password;
        $this->u2fResponse = $u2fResponse;
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getU2fResponse(): ?string
    {
        return $this->u2fResponse;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function setU2fResponse(?string $u2fResponse): void
    {
        $this->u2fResponse = $u2fResponse;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
}