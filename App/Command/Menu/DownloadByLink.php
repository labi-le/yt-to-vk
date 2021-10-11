<?php
declare(strict_types=1);

namespace App\Command\Menu;

use App\Command\Download;
use App\Entity\PornHubVideo;
use App\Entity\TikTokVideo;
use App\Service\Video\RegexEnum;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;

#[MessageNew]
final class DownloadByLink extends BaseCommands
{
    #[MessageRegex(RegexEnum::REGEX_TIKTOK)]
    public function downloadTiktok(Data $data): void
    {
        preg_match(RegexEnum::REGEX_TIKTOK, $data->getText(), $matches);
        (new Download)->download(
            (new TikTokVideo())
                ->setId($matches[1]),
            $data
        );
    }

    #[MessageRegex(RegexEnum::REGEX_PORNHUB)]
    public function downloadPornhub(Data $data): void
    {
        preg_match(RegexEnum::REGEX_PORNHUB, $data->getText(), $matches);
        (new Download)->download(
            (new PornHubVideo())
                ->setId($matches[1]),
            $data
        );
    }
}