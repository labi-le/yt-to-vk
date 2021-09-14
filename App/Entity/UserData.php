<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="userdata")
 */
class UserData
{
    /**
     * @Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @Column(type="integer", unique=true, nullable=false)
     */
    private int $id;

    /**
     * @Column(type="string", unique=true, nullable=true)
     */
    private ?string $access_token = null;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    /**
     * @param string|null $access_token
     * @return UserData
     */
    public function setAccessToken(?string $access_token): UserData
    {
        $this->access_token = $access_token;
        return $this;
    }


}