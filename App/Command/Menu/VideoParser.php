<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Service\Video\Render;
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
    private const PATTERN = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

    /**
     * Генерация превью и кнопок
     * @param Data $data
     * @throws \Throwable
     */
    #[MessageRegex(self::PATTERN)]
    public function url(Data $data): void
    {
        preg_match(self::PATTERN, $data->getText(), $matches);

        [, $id] = $matches;
        try {
            Render::preview($data, $id);
        } catch (\Throwable $e) {
            $this->message($data->getPeerId(), "Не удалось загрузить превью\n\nWhy?\n" . $e->getMessage());
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