<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordRequest
{
    /**
     * @Assert\NotBlank()
     */
    public $currentPassword;

    /**
     * @Assert\NotBlank()
     */
    public $newPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
}
