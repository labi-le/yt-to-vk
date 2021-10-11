<?php
declare(strict_types=1);

use App\Service\Video\VideoEnum;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\Payload;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;

#[MessageNew]
#[Conversation(Conversation::PERSONAL_DIALOG)]
final class StaticMenu
{
    #[Payload([VideoEnum::HELP => VideoEnum::DOWNLOAD])]
    public function howToDownloadVideo(Data $data): bool
    {
        Create::new(
            (new Message())
                ->setMessage("Для загрузки видео необходимо скинуть ссылку,
      например:
        https://youtu.be/91tdYToLJ-w
        youtu.be/91tdYToLJ-w")
                ->setDontParseLinks(true)
                ->setPeerId($data->getPeerId())
        );

        return false;
    }
}