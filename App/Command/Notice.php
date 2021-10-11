<?php
declare(strict_types=1);

namespace App\Command;

use Astaroth\Attribute\Attachment;
use Astaroth\Attribute\Conversation;
use Astaroth\Attribute\Event\MessageNew;
use Astaroth\Commands\BaseCommands;
use Astaroth\DataFetcher\Events\MessageNew as Data;

#[MessageNew]
#[Conversation(Conversation::ALL)]
final class Notice extends BaseCommands
{
    /**
     * Нотайс если юзер отправил вместо ссылки на видео - вложение
     * @param Data $data
     * @return bool
     * @throws \Throwable
     */
    #[Attachment(Attachment::VIDEO)]
    public function videoAttachmentNotice(Data $data): bool
    {
        $this->message($data->getPeerId(), "Не прикрепляй видео, а просто отправь ссылку на него");
        return false;
    }

    public function invalidOrMissingToken(Data $data): bool
    {
        $this->message($data->getPeerId(), "Ты похоже забыл установить токен, либо предыдущий устарел!
      
Зачем боту нужен токен?
▶ Токен необходим для загрузки видео (видео будет загружено под твоим именем в категорию `Видео` в этой группе)
Как установить токен?
▶ Перейди по ссылке ниже, предоставь права боту, и отправь ссылку из адресной строки, дальше я всё сделаю сам
🔑 https://vk.cc/aAoQHd
▶ Также необходимо быть подписанным на паблик так как видео публикуются в группу от твоего имени!");
        return false;
    }
}