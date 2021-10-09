<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Entity\YouTubeVideo;
use App\Service\Video\Render;
use App\Service\Video\ServiceEnum;
use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;


#[Conversation(Conversation::PERSONAL_DIALOG)]
#[MessageNew]
class VideoParser extends BaseCommands
{
    /**
     * Генерация превью и кнопок
     * @param Data $data
     * @throws \Throwable
     */
    #[MessageRegex(ServiceEnum::REGEX_YOUTUBE)]
    public function previewYoutube(Data $data): void
    {
        preg_match(ServiceEnum::REGEX_YOUTUBE, $data->getText(), $matches);

        [$url, $id] = $matches;
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

    /**
     * Нотайс если юзер отправил вместо ссылки на видео - вложение
     * @param Data $data
     * @throws \Throwable
     */
    #[Attachment(Attachment::VIDEO)]
    public function videoAttachmentNotice(Data $data): void
    {
        $this->message($data->getPeerId(), "Не прикрепляй видео, а просто отправь ссылку на него");
    }
}