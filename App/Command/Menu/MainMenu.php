<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Entity\TikTokVideo;
use App\Entity\UserData;
use App\Entity\YouTubeVideo;
use App\Service\Video\Cache;
use App\Service\Video\Downloader;
use App\Service\Video\InvalidAccessTokenException;
use App\Service\Video\MissingAccessTokenException;
use App\Service\Video\Render;
use App\Service\Video\ServiceEnum;
use App\Service\Video\UserConfigurator;
use App\Service\Video\VideoEnum;
use App\Service\Video\VideoInterface;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;

#[Conversation(Conversation::PERSONAL_DIALOG)]
#[MessageNew]
class MainMenu extends BaseCommands
{

    #[MessageRegex(ServiceEnum::REGEX_TIKTOK)]
    public function downloadTiktok(Data $data): void
    {
        preg_match(ServiceEnum::REGEX_TIKTOK, $data->getText(), $matches);
        $this->download(
            (new TikTokVideo())
                ->setId($matches[1]),
            $data
        );
    }

    /**
     * Нажатие по кнопке загрузить
     * @param Data $data
     * @throws \Throwable
     */
    #[Payload([VideoEnum::MENU => VideoEnum::DOWNLOAD], Payload::CONTAINS)]
    public function downloadYoutube(Data $data): void
    {
        $id = $data->getPayload()["id"] ?? null;

        $this->download(
            (new YouTubeVideo())
                ->setId($id),
            $data
        );
    }

    private function download(VideoInterface $service, Data $data): void
    {
        $cache = Cache::get($service);
        try {
            $message = (new Message())
                ->setPeerId($data->getPeerId())
                ->setMessage("Загрузка начата")
                ->setAttachment("photo-190405359_457241820");

            $response = Create::new($message);
            $messageId = $response[0][0]["conversation_message_id"];

            if ($cache === null) {
                $video = Downloader::upload(
                    $service,
                    new UserData($data->getPeerId()),
                    $this->onProgressMessage($message, $messageId)
                );

                Cache::cache($video);
            }
            $this->messagesEdit(Render::uploadedVideo($data, $service), $messageId);
        } catch (MissingAccessTokenException $e) {
            $this->message($data->getPeerId(), "Не удалось загрузить видео\n\nWhy?\n" . $e->getMessage());
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
    }

    /**
     * Валидируем и сохраняем User Access Token
     * @param Data $data
     * @throws \Throwable
     */
    #[MessageRegex("/(?!access_token=)[\w]*(?=&expires)/")]
    #[MessageRegex("/[\w]{80,90}/")]
    public function writeAccessToken(Data $data): void
    {
        preg_match("/[\w]{80,90}/", $data->getText(), $m);

        try {
            UserConfigurator::setAccessToken(
                (new UserData($data->getPeerId()))
                    ->setAccessToken($m[0])
            );
        } catch (InvalidAccessTokenException $e) {
            $this->message($data->getPeerId(), "Не удалось изменить токен\n\nWhy?\n" . $e->getMessage());
            return;
        }

        $this->message($data->getPeerId(), "Токен установлен");
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
            if ($delayCounter === 20){
                $message->setMessage($text);
                $this->messagesEdit($message, $messageId);
                $delayCounter = 0;
            }
        };
    }
}