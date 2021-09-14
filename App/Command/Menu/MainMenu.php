<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Entity\UserData;
use App\Service\Video\Cache;
use App\Service\Video\Downloader;
use App\Service\Video\InvalidAccessTokenException;
use App\Service\Video\MissingAccessTokenException;
use App\Service\Video\Render;
use App\Service\Video\UserConfigurator;
use App\Service\Video\VideoEnum;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Attribute\Payload;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Create;
use Astaroth\VkUtils\Builders\Message;
use YouTube\Exception\TooManyRequestsException;
use YouTube\Exception\YouTubeException;

#[Conversation(Conversation::PERSONAL_DIALOG)]
#[MessageNew]
class MainMenu extends BaseCommands
{
    /**
     * Нажатие по кнопке загрузить
     * @param Data $data
     * @param Create $create
     * @throws \Throwable
     */
    #[Payload([VideoEnum::MENU => VideoEnum::DOWNLOAD], Payload::CONTAINS)]
    public function download(Data $data, Create $create): void
    {
        $video = $data->getPayload()["id"] ?? null;

        $cache = Cache::get($video);
        try {
            $response = $create(
                (new Message())
                    ->setPeerId($data->getPeerId())
                    ->setMessage("Загрузка начата")
                    ->setAttachment("photo-190405359_457241820")
            );

            $messageId = $response[0][0]["conversation_message_id"];

            if ($cache === null) {
                $video = Downloader::upload(
                    $video,
                    new UserData($data->getPeerId())
                );

                Cache::cache($video);
            }

            $this->messagesEdit(Render::uploadedVideo($data, $video), $messageId);
        } catch (MissingAccessTokenException $e) {
            $this->message($data->getPeerId(), "Не удалось загрузить видео\n\nWhy?\n" . $e->getMessage());
        } catch (TooManyRequestsException | YouTubeException $e) {
            $this->message($data->getPeerId(), "Youtube Error\nWhy?\n" . $e->getMessage());
        } catch (\Exception | \Throwable $e) {
            $this->message($data->getPeerId(), "Неперехваченная ошибка (её лучше переслать разработчику бота)\n\n" . $e->getMessage() . "\n\nFrom file:\n" . $e->getFile() . "\n\nLine:\n" . $e->getLine());
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

//        $this->message($data->getPeerId(), print_r($m, true));
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
}