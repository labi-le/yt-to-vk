<?php

use DigitalStars\SimpleVK\SimpleVK as vk;

$videoID = $videoID ?? $payload['dl']['download']['id'];

try {
  $yt = new YoutubeParse($videoID);
} catch (\Throwable $th) {
  die($vk->msg('🤬 Флуд контроль от YouTube, подожди пару секунд и попробуй еще раз')->send());
}

$video = $yt->get_data();
$video['author'] = $video['author_name'];

$isDownload = YoutubeDB::get_video($videoID);

//если видос есть в базе
if ($isDownload) {
  $vk->msg('Опа, нашёл у себя в помойке видос')->addAttachment($isDownload)->send();;
} else {

  //проверям токен юзера на актуальность
  try {
    $verifyToken = vk::create(User::getToken($user_id), VK_VERSION)->request('account.getProfileInfo', []);
  } catch (Exception $e) {
    die($vk->msg('
    Токен устарел, попробуй его обновить
    
    Как обновить токен?
    ▶ Перейди по ссылке ниже, предоставь права боту, и установи токен командой settoken *ссылка из адресной строки*
    🔑 https://vk.cc/aAoQHd')->send());
  }


  $message_id = $vk->msg('⚙ Загрузка начата')->send();

  function api($method, array $params)
  {
    $data = file_get_contents(API_SLIVA . $method . '.php?' . http_build_query($params));
    return json_decode($data, true);
  }

  $download = api('download', ['id' => $video['id']]);
  sleep(2);

  if ($download['status'] === true) {
    $vk->editMessage($id, $message_id, ['message' => '🖥️ Видео загружено на сервер, размер файла ' . $download['size'] . "\n🖥 Загрузка на сервера ВК"]);

    //если видос успешно загрузился на сервер, то загружаем его втентакли
    $upload = api('upload', [
      'id' => $video['id'],
      'author' => $video['author'],
      'title' => $video['title'],
      // 'description' => $video['description'],
      'description' => 'Описание съели злые волки',
      // 'album_id' => intval($local_album['album_id']),
      'album_id' => false,
      'group_id' => $data['group_id'],
      'access_token' => User::getToken($user_id),
      'filename' => $download['filename']
    ]);
    sleep(2);

    if ($upload['status'] === true) {
      $vk->editMessage($id, $message_id, [
        'message' => "✅ Видео загружено\n\n\n" . $video['title'],
        'attachment' => $upload['attachment']
      ]);

      YoutubeDB::add_video($videoID, $upload['attachment'], $video['author']);

      //удаляем видео с сервера
      api('delete', ['filename' => $download['filename']]);
      sleep(1);
    } else {
      $vk->editMessage($id, $message_id, ['message' => '🤬 Не удалось загрузить видео на сервера ВК']);
    }
  } else $vk->editMessage($id, $message_id, ['message' => '🤬 Не удалось загрузить видео с YouTube']);
}
