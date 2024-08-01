<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    // Add fields: id, name, email, username, address, role
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $name;

    /** @ORM\Column(type="string", length=255) */
    private $email;

    /** @ORM\Column(type="string", length=255) */
    private $username;

    /** @ORM\Column(type="string", length=255) */
    private $address;

    /** @ORM\Column(type="string", length=50) */
    private $role;

    // Add getters and setters
}
