<?php

use DigitalStars\SimpleVk\Message as Message;
use DigitalStars\SimpleVK\SimpleVK as vk;


require('video/other.php');

if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $message, $match)){
  $videoID = $match[1];

  if(User::status_menu($user_id)){
    require('video/downloadVideo.php');
  } else require('video/preview.php');
  

}elseif ($attachments['video'][0]['platform'] == 'YouTube') {
  $vk->msg('Не прикрепляй видео, а просто отправь ссылку на него')->send();
  }

if(substring_lower[0] == 'settoken' and substring_lower[1]){
    $tokenStart = substr(stristr(substring[1], '='), 1);
    $tokenEnd = stristr($tokenStart, '&', true);

    try {
        $verifyToken = vk::create($tokenEnd, VK_VERSION)->request('account.getProfileInfo', []);
    } catch (Exception $e) {
        die($vk->msg('Неверный токен')->send());
    }

    if (is_array($verifyToken)){
        User::setToken($user_id, $tokenEnd);
        $vk->msg('Токен установлен, можешь заружать видосики :)')->send();
    }
}

if($payload['dl']){

  $userData = User::get($user_id);

  if(!$userData){
      die($vk->msg('Тебя нет в системе, напиши Начать')->send());
  } elseif( !$userData->token or is_null($userData->token) or $userData->token === 0 ){
      die($vk->msg('Установи токен для загрузки видео или смотри уже загруженные!
      
Зачем боту нужен токен?
▶ Токен необходим для загрузки видео в группу (раньше я использовал свои аккаунты, но так как пользователей много вк ставит палки в колеса)

Как установить токен?
▶ Перейди по ссылке ниже, предоставь права боту, и установи токен командой settoken *ссылка из адресной строки*
🔑 https://vk.cc/aAoQHd

▶ Также необходимо быть подписанным на паблик так как видосы публикуются в группу от твоего имени!')->send());
  }

  switch ( key($payload['dl'] ) ) {

    case 'download':
      // die($vk->reply('На данный момент на сервере идут технические работы'));
      require('video/downloadVideo.php');
      break;

    case 'cancel':
      $vk->msg('ok')->send();
      break;

  }

}