<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\PornHubVideo;
use App\Entity\TikTokVideo;
use App\Entity\YouTubeVideo;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Support\Facades\Create;
use Astaroth\Support\Facades\Upload;
use Astaroth\VkKeyboard\Contracts\Keyboard\Button\FactoryInterface;
use Astaroth\VkKeyboard\Facade;
use Astaroth\VkUtils\Builders\Attachments\Message\PhotoMessages;
use Astaroth\VkUtils\Builders\Message;

class Render
{
    /**
     * @throws \Throwable
     */
    public static function preview(MessageNew $data, YouTubeVideo $id): void
    {
        self::generate($id, static function (YouTubeVideo $video, string $text, ?string $preview) use ($data) {
            $keyboard = Facade::createKeyboardInline(static function (FactoryInterface $factory) use ($video) {
                return [
                    [
                        $factory->callback(VideoEnum::DOWNLOAD,
                            [
                                VideoEnum::MENU => VideoEnum::DOWNLOAD,
                                VideoEnum::ID => $video->getId()
                            ]
                        ),
                    ]
                ];
            });

            Create::new(
                (new Message())
                    ->setMessage($text)
                    ->setKeyboard($keyboard)
                    ->setAttachment($preview)
                    ->setPeerId($data->getPeerId())
            );
        });
    }

    /**
     * @throws \Throwable
     */
    public static function uploadedVideo(MessageNew $data, string|VideoInterface $video): Message
    {
        return self::generate($video, static function (VideoInterface $video, string $text) use ($data) {
            return (new Message())
                ->setPeerId($data->getPeerId())
                ->setMessage($text)
                ->setAttachment($video->getVkAttachment())
                ->setDontParseLinks(true);
        }, false);
    }

    /**
     * @param VideoInterface $video
     * @param callable $func (YouTubeVideo $video, string $text, ?string $preview)
     * @param bool $renderPreview
     * @return mixed
     * @throws \Exception
     */
    private static function generate(VideoInterface $video, callable $func, bool $renderPreview = true): mixed
    {
        $videoFromCache = Cache::get($video);
        if ($videoFromCache === null && $video instanceof YouTubeVideo) {
            $video = VideoService::get($video);
            $preview = $renderPreview === false ?: self::generatePreview($video->getPreview());
        }
        $text = self::generateText($video);

        return $func($video, $text, $preview ?? null);
    }

    /**
     * @throws \Exception
     */
    private static function generatePreview(string $thumbnail_url)
    {
        return Upload::attachments(new PhotoMessages($thumbnail_url))[0];
    }

    /**
     * @throws VideoServiceNotFoundException
     */
    private static function generateText(VideoInterface $video): string
    {
        if ($video instanceof YouTubeVideo) {
            return "🗣 {$video->getAuthor()}\n📹 {$video->getTitle()}";
        }

        if ($video instanceof PornHubVideo) {
            return "📹 {$video->getTitle()}";
        }

        if ($video instanceof TikTokVideo) {
            return "🗣 {$video->getTitle()}";
        }

        throw new VideoServiceNotFoundException("Не реализован сервис обработки данного видео");
    }
}