<?php
declare(strict_types=1);

namespace App\Service\Video;

interface VideoInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     * @return static
     */
    public function setId(string $id): static;

    /**
     * @return string
     */
    public function getVkAttachment(): string;

    /**
     * @param string $vk_attachment
     * @return static
     */
    public function setVkAttachment(string $vk_attachment): static;

    public function getLink(): string;

    /**
     * @param string|null $author
     * @return $this
     */
    public function setAuthor(?string $author): static;
    public function getAuthor(): ?string;

    public function setTitle(?string $title): static;
    public function getTitle(): ?string;
}