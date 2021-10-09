<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="tiktok_list")
 */
final class TikTokVideo extends AbstractVideoEntity
{
    public function getLink(): string
    {
        return "https://vm.tiktok.com/" . $this->getId();
    }
}