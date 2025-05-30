<?php

namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;

class SetupDTO
{
    public function __construct(
        #[Assert\Email]
        public string $email = "",

        #[Assert\NotBlank]
        public string $password = "",

        #[Assert\NotBlank]
        public string $secret = ""
    )
    {
    }
}