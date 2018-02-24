<?php

namespace App\Submission;

class RegistrationSubmission
{
    private $password;

    private $username;

    public function __construct(
        ?string $password = null,
        ?string $username = null)
    {
        $this->password = $password;
        $this->username = $username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
}