<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Video\VideoEnum;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Attribute\Message as MessageAttribute;
use Astaroth\Attribute\Payload;
use Astaroth\DataFetcher\Events\MessageNew as Data;
use Astaroth\Support\Facades\Create;
use Astaroth\VkKeyboard\Contracts\Keyboard\Button\FactoryInterface;
use Astaroth\VkKeyboard\Facade;
use Astaroth\VkKeyboard\Object\Keyboard\Button\Text;
use Astaroth\VkUtils\Builders\Message;

#[Conversation(Conversation::ALL)]
#[MessageNew]
final class Help
{
    /**
     * @throws \Throwable
     */
    #[
        MessageAttribute("помощь", MessageAttribute::START_AS), MessageAttribute("help", MessageAttribute::START_AS),
        MessageAttribute("menu", MessageAttribute::START_AS), MessageAttribute("/start", MessageAttribute::START_AS),
        MessageAttribute("начать", MessageAttribute::START_AS), MessageAttribute("/начать", MessageAttribute::START_AS),
        MessageAttribute("хелп", MessageAttribute::START_AS), MessageAttribute("памагити", MessageAttribute::START_AS),
        MessageAttribute("помогите", MessageAttribute::START_AS), MessageAttribute("сука как", MessageAttribute::START_AS),
        MessageAttribute("а как", MessageAttribute::START_AS)
    ]
    public function help(Data $data, Create $create): void
    {
        $keyboard = Facade::createKeyboardBasic(static function (FactoryInterface $factory) {
            return [
                [
                    $factory->callback("Загрузка видео", [VideoEnum::HELP => VideoEnum::DOWNLOAD]),
                ]
            ];
        });
        $create(
            (new Message())
                ->setMessage("Что тебе интересно узнать?")
                ->setKeyboard($keyboard)
                ->setPeerId($data->getPeerId())
        );
    }
}