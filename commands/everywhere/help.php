<?php

use DigitalStars\SimpleVk\Message as Message;

if (in_array(string_lower, ['помощь', 'хелп', 'хэлп', 'help', '/помощь', '/help', 'хелп'])  and !$chat_id) {

  $kb_yt = $vk->buttonText('Загрузка видео с YouTube', 'red', ['help' => 'yt']);
  $kb_other = $vk->buttonText('Остальное', 'secondary', ['help' => 'other']);

  Message::create($vk)
    ->text('Что тебе интересно узнать?
       (Используй кнопки)')
    ->kbd([[$kb_yt]], $inline = false, $one_time = true)
    ->send($id);
}

if (isset($payload)) {
  switch ($payload['help']) {
    case 'yt':
      $vk->reply('Для загрузки видео необходимо скинуть ссылку,

      например:
        https://youtu.be/91tdYToLJ-w
        youtu.be/91tdYToLJ-w', ['dont_parse_links' => 1]);
      break;
  }
}
