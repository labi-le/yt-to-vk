<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\UserData;
use App\Service\Video\Cache;
use App\Service\Video\Downloader;
use App\Service\Video\MissingAccessTokenException;
use App\Service\Video\Render;
use App\Service\Video\VideoInterface;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as MessageNewData;
use Astaroth\DataFetcher\Events\MessageEvent as MessageEventData;
use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;

final class Download extends BaseCommands
{
    public function download(VideoInterface $service, MessageNewData|MessageEventData $data): bool
    {
        $videoFromCache = Cache::get($service);
        try {
            $message = (new Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("Загрузка начата")
                ->setAttachment("photo-190405359_457241820");

            $response = Create::new($message);
            $messageId = $response[0][0]["conversation_message_id"];

            if ($videoFromCache === null) {
                Downloader::upload(
                    $service,
                    new UserData($data->getPeerId()),
                    $this->onProgressMessage($message, $messageId)
                );

                Cache::cache($service);
            } else {
                $service = $videoFromCache;
            }
            $this->messagesEdit(Render::uploadedVideo($data, $service), $messageId);
        } catch (MissingAccessTokenException) {
            return (new Notice)->invalidOrMissingToken($data);
        } catch (\Exception | \Throwable $e) {
            $message = sprintf(
                "Неперехваченная ошибка (её лучше переслать разработчику бота)\n\nException: %s\n\n%s\n\nLine: %s\n\nFile\n%s",
                \get_class($e),
                $e->getMessage(),
                $e->getLine(),
                $e->getFile()
            );

            $this->message($data->getPeerId(), $message);
        }

        return true;
    }

    private function onProgressMessage(Message $message, int $messageId): \Closure
    {
        return function (?string $progressTarget, string $percentage, string $size, string $speed, string $eta, ?string $totalTime) use ($message, $messageId): void {
            static $delayCounter = 0;

            $text = "Загрузка файла: $progressTarget\nЗагружено: $percentage\nРазмер файла: $size\n";
            if ($speed) {
                $text .= "Скорость загрузки: $speed\n";
            }
            if ($eta) {
                $text .= "Осталось: $eta\n";
            }
            if ($totalTime !== null) {
                $text .= "Загружено за: $totalTime\n";
            }

            $delayCounter++;
            if ($delayCounter === 50) {
                $message->setMessage($text);
                $this->messagesEdit($message, $messageId);
                $delayCounter = 0;
            }
        };
    }
}