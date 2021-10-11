<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Entity\YouTubeVideo;
use App\Service\Video\Render;
use App\Service\Video\RegexEnum;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;


#[Conversation(Conversation::PERSONAL_DIALOG)]
#[MessageNew]
final class VideoParser extends BaseCommands
{
    /**
     * Генерация превью и кнопок
     * @param Data $data
     * @throws \Throwable
     */
    #[MessageRegex(RegexEnum::REGEX_YOUTUBE)]
    public function previewYoutube(Data $data): void
    {
        preg_match(RegexEnum::REGEX_YOUTUBE, $data->getText(), $matches);

        [, $id] = $matches;
        try {
            Render::preview(
                $data,
                (new YouTubeVideo())
                    ->setId($id)
            );
        } catch (\Exception $e) {
            $message = sprintf(
                "Не удалось загрузить превью\n\nWhy?\n%s\n\nLine: %s\n\nFile\n%s",
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );
            $this->message($data->getPeerId(), $message);
        }
    }
}