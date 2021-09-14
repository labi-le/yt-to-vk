<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @Table(name="analytics")
 */
class Analytics
{
    /**
     * @Id
     * @Column(type="integer")
     * @OneToMany(targetEntity="App\Entity\UserData", mappedBy="id")
     * @var int
     */
    private int $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return void
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTotalSpentTime(): int
    {
        return $this->total_spent_time;
    }

    /**
     * @param int $total_spent_time
     * @return Analytics
     */
    public function setTotalSpentTime(int $total_spent_time): Analytics
    {
        $this->total_spent_time = $total_spent_time;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCountVideo(): int
    {
        return $this->total_count_video;
    }

    /**
     * @param int $total_count_video
     * @return Analytics
     */
    public function setTotalCountVideo(int $total_count_video): Analytics
    {
        $this->total_count_video = $total_count_video;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalSizeAllVideo(): int
    {
        return $this->total_size_all_video;
    }

    /**
     * @param int $total_size_all_video
     * @return Analytics
     */
    public function setTotalSizeAllVideo(int $total_size_all_video): Analytics
    {
        $this->total_size_all_video = $total_size_all_video;
        return $this;
    }

    /**
     * @Column(type="integer", nullable=false)
     * @var int
     */
    private int $total_spent_time;

    /**
     * @Column(type="integer", nullable=false)
     * @var int
     */
    private int $total_count_video;

    /**
     * @Column(type="integer", nullable=false)
     * @var int
     */
    private int $total_size_all_video;
}