<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="youtube_list")
 */
final class YouTubeVideo extends AbstractVideoEntity
{
    private ?string $preview;

    /**
     * @return string
     */
    public function getPreview(): string
    {
        return $this->preview;
    }
    /**
     * @param string $preview
     * @return YouTubeVideo
     */
    public function setPreview(string $preview): YouTubeVideo
    {
        $this->preview = $preview;
        return $this;
    }

    public function getLink(): string
    {
        return "https://www.youtube.com/watch?v=" . $this->getId();
    }
}