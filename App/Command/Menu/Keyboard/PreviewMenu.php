<?php
declare(strict_types=1);

use App\Command\Download;
use App\Entity\YouTubeVideo;
use App\Service\Video\VideoEnum;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageEvent;
use Astaroth\Attribute\Payload;
use Astaroth\DataFetcher\Events\MessageEvent as MessageEventData;

#[MessageEvent]
#[Conversation(Conversation::PERSONAL_DIALOG)]
final class PreviewMenu
{
    /**
     * Нажатие по кнопке загрузить
     * @param MessageEventData $data
     */
    #[Payload([VideoEnum::MENU => VideoEnum::DOWNLOAD], Payload::CONTAINS)]
    public function downloadYoutube(MessageEventData $data): void
    {
        $id = $data->getPayload()["id"] ?? null;

        (new Download)->download(
            (new YouTubeVideo())
                ->setId($id),
            $data
        );
    }
}