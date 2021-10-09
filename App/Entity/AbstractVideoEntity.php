<?php

declare(strict_types=1);

namespace App\Entity;

use App\Service\Video\VideoInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * @MappedSuperclass
 */
abstract class AbstractVideoEntity implements VideoInterface
{
    /**
     * @Id
     * @Column(type="string", unique=true, nullable=false)
     */
    protected string $id;

    /**
     * @Column(type="string", unique=true, nullable=true)
     */
    protected string $vk_attachment;

    /**
     * @Column(type="string", nullable=true)
     */
    protected ?string $author = null;

    /**
     * @Column(type="string", nullable=true)
     */
    protected ?string $title = null;

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::class;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return static
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getVkAttachment(): string
    {
        return $this->vk_attachment;
    }

    /**
     * @param string $vk_attachment
     * @return static
     */
    public function setVkAttachment(string $vk_attachment): static
    {
        $this->vk_attachment = $vk_attachment;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param ?string $author
     * @return static
     */
    public function setAuthor(?string $author): static
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return static
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }
}