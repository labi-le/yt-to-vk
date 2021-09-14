<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="video")
 */
class Video
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Video
     */
    public function setId(string $id): Video
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     * @return Video
     */
    public function setAuthor(?string $author): Video
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Video
     */
    public function setTitle(string $title): Video
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->preview;
    }

    /**
     * @param string|null $preview
     * @return Video
     */
    public function setPreview(?string $preview): Video
    {
        $this->preview = $preview;
        return $this;
    }

    /**
     * @Id
     * @Column(type="string", unique=true, nullable=false)
     */
    private string $id;

    /**
     * @Column(type="string", nullable=true)
     */
    private ?string $author;

    /**
     * @Column(type="string", nullable=true)
     */
    private ?string $title;

    /**
     * @Column(type="string", unique=true, nullable=true)
     */
    private ?string $preview;

    /**
     * @Column(type="string", unique=true, nullable=true)
     */
    private string $vk_attachment;

    /**
     * @return string
     */
    public function getVkAttachment(): string
    {
        return $this->vk_attachment;
    }

    /**
     * @param string $vk_attachment
     * @return Video
     */
    public function setVkAttachment(string $vk_attachment): Video
    {
        $this->vk_attachment = $vk_attachment;
        return $this;
    }

    private bool $isCached = false;

    /**
     * @return bool
     */
    public function isCached(): bool
    {
        return $this->isCached;
    }

    /**
     * @param bool $isCached
     * @return Video
     */
    public function setCached(bool $isCached): Video
    {
        $this->isCached = $isCached;
        return $this;
    }
}