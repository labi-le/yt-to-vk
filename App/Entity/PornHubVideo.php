<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="pornhub_list")
 */
final class PornHubVideo extends AbstractVideoEntity
{
    public function getLink(): string
    {
        return "https://rt.pornhub.com/view_video.php?viewkey=" . $this->getId();
    }
}