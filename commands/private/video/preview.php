<?php

use DigitalStars\SimpleVk\Message as Message;
use DigitalStars\SimpleVK\SimpleVK as vk;

$message_id = $vk->msg('🤔 Получаю данные о видео')->send();

$cmd = new YoutubeParse($videoID);
$video = $cmd->get_data();

$vk->deleteMessage($message_id);

$fulltext = '👤 ' . $video['author_name'] .
  "\n\n📝 " . $video['title'];

$videoInfo =
  [
    'id' => $video['id']
  ];

$kb_dl = $vk->buttonText('Download', 'white', ['dl' => ['download' => $videoInfo]]);
$kb_cancel = $vk->buttonText('Cancel', 'red', ['dl' => ['cancel' => true]]);


$message_id = Message::create($vk)
  ->text($fulltext)
  ->img($video['preview'])
  ->kbd([[$kb_dl], [$kb_cancel]], true, false)
  ->send($id);
