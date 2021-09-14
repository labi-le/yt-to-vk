<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\UserData;
use Astaroth\Support\Facades\Upload;
use Astaroth\VkUtils\Builders\Attachments\Video;
use YouTube\Exception\TooManyRequestsException;
use YouTube\Exception\YouTubeException;
use YouTube\YouTubeDownloader;

class Downloader
{
    /**
     * @throws YouTubeException
     * @throws TooManyRequestsException
     * @throws \Exception
     */
    public static function upload(string $id, UserData $userData): \App\Entity\Video
    {
        $access_token = UserConfigurator::getAccessToken($userData);

        $videoEntity = VideoService::get($id);

        $downloadInformation = (new YouTubeDownloader())->getDownloadLinks($id)->getFirstCombinedFormat();

        $downloadUrl = $downloadInformation?->url;
        if ($downloadUrl) {
            $upload_new_token = Upload::changeToken($access_token);
            $attachment = $upload_new_token->upload(
                (new Video(self::download($downloadUrl)))
                    ->setIsPrivate(true)
                    ->setName($videoEntity->getTitle())
                    ->setGroupId(UPLOAD_GROUP)
                    ->setDescription("Описание съели злые волки!!!")
            )[0];

            return $videoEntity
                ->setCached(true)
                ->setVkAttachment($attachment);
        }

        return throw new \Exception("Не удалось загрузить видео");
    }

    /**
     * Download video to local machine
     * @param string $directLink
     * @return string
     * @throws \Exception
     */
    private static function download(string $directLink): string
    {
        $filename = tempnam(sys_get_temp_dir(), (string)random_int(0, 2));
        copy($directLink, $filename);
        register_shutdown_function(static fn() => @unlink($filename));

        return $filename;
    }
}