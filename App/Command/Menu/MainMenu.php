<?php

declare(strict_types=1);

namespace App\Command\Menu;

use App\Entity\UserData;
use App\Service\Video\InvalidAccessTokenException;
use App\Service\Video\UserConfigurator;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\MessageRegex;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;

#[Conversation(Conversation::PERSONAL_DIALOG)]
#[MessageNew]
final class MainMenu extends BaseCommands
{
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
}