<?php

use DigitalStars\SimpleVk\Message as Message;
use DigitalStars\SimpleVK\SimpleVK as vk;



if (string_lower == '/switch_preview') {
  User::switch_preview($user_id);
  $vk->msg('Настройки применены')->send();
}

if (in_array(mb_strtolower($str = string), ['начать', 'меню']) or $payload == 'Начать') {

  if (!User::get($user_id)) User::create($user_id);

  $preview = $vk->buttonText('Превью', $boolean_color[User::status_menu($user_id)], ['main_menu' => [
    'action' => 'preview',
    'id' => $user_id
  ]]);

  $pay = $vk->buttonOpenLink($donate_url, 'Помочь с оплатой сервера');


  $message_id = Message::create($vk)
    ->text('Добро пожаловать')
    ->kbd([[$preview, $pay]], false, false)
    ->send();
}

if ($payload['main_menu']['action']) {
  User::switch_preview($user_id);
  $preview = $vk->buttonText('Превью', $boolean_color[User::status_menu($user_id)], ['main_menu' => [
    'action' => 'preview'
  ]]);

  $pay = $vk->buttonOpenLink($donate_url, 'Помочь с оплатой сервера');

  $message_id = Message::create($vk)
    ->text('Настройки применены')
    ->kbd([[$preview, $pay]], false, false)
    ->send();
}
