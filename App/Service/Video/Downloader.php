<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\UserData;
use App\Entity\YouTubeVideo;
use Astaroth\Support\Facades\Upload;
use Astaroth\VkUtils\Builders\Attachments\Video;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class Downloader
{
    /**
     * @param VideoInterface $video
     * @param UserData $userData
     * @param callable $onProgress
     * @return VideoInterface
     * @throws InvalidAccessTokenException
     * @throws MissingAccessTokenException
     */
    public static function upload(VideoInterface $video, UserData $userData, callable $onProgress): VideoInterface
    {
        $access_token = UserConfigurator::getAccessToken($userData);

//        reference
//        if ($video instanceof YouTubeVideo) {
//            VideoService::get($video);
//        }

        $upload_new_token = Upload::changeToken($access_token);

        $videoPath = self::download($video, $onProgress);
        $attachment = $upload_new_token->upload(
            (new Video($videoPath))
                ->setIsPrivate(true)
                ->setName($video->getTitle())
                ->setGroupId(UPLOAD_GROUP)
                ->setDescription("Описание съели злые волки!!!")
        )[0];

        @unlink($videoPath);

        return $video->setVkAttachment($attachment);

    }

    /**
     * Download video to local machine
     * @param VideoInterface $video
     * @param callable $onProgress
     * @return string
     * @throws \Exception
     */
    private static function download(VideoInterface $video, callable $onProgress): string
    {
        $yt = new YoutubeDl();
        $yt->setBinPath("youtube-dl");
        $yt->onProgress($onProgress);

        $collection = $yt->download(
            Options::create()
                ->downloadPath(sys_get_temp_dir())
                ->url($video->getLink())
                ->output('%(title)s.%(ext)s')
        );

        $downloadedVideo = current($collection->getVideos());
        $filePath = $downloadedVideo->getFilename();

        $video
            ->setTitle($downloadedVideo->getTitle())
            ->setAuthor($downloadedVideo->getChannel());

        if ($filePath === null) {
            throw new \Exception("Не удалось получить название файла");
        }

        return $filePath;
    }
}