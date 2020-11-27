<?php

require './vendor/autoload.php';
require_once('config.php');

use DigitalStars\simplevk\Message;
use DigitalStars\SimpleVK\SimpleVK as vk;

$vk = vk::create(VK_TOKEN, VK_VERSION)->setConfirm(VK_CONFIRM_KEY);

$data = $vk->initVars($id, $user_id, $type, $message, $payload, $msg_id, $attachments);

if ($user_id < 0) exit();


R::setup(DB_HOST, DB_LOGIN, DB_PASSWORD);

if (VIDEO_DEBUG == true) $vk->setUserLogError(GOD[0]);

define('string', $message);
define('substring', explode(' ', $message));

define('string_lower', mb_strtolower($message));
define('substring_lower', explode(' ', mb_strtolower($message)));


$peer_id = $data['object']['message']['peer_id'];
$chat_id = $peer_id - 2e9;
$chat_id = $chat_id > 0 ? $chat_id : false;


switch ($type) {

  case 'message_event':

    switch ($payload) {
    }

    break;
  case 'message_new':

    foreach (glob("commands/everywhere/*.php") as $file) require $file; // команды работающие везде 


    if (!$chat_id) { //если личное сообщение
      require('commands/private/video.php');
      require('commands/private/mainMenu.php');
    } elseif ($chat_id) { //если пишут в беседе

      foreach (glob("commands/conversation/*.php") as $file) require $file;
    }
    break;
}
