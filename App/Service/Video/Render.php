<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\Video;
use Astaroth\DataFetcher\Events\MessageNew;
use Astaroth\Foundation\Utils;
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
    public static function preview(MessageNew $data, string|Video $id): void
    {
        self::generate($id, static function (Video $video, string $text, ?string $preview) use ($data) {
            $keyboard = Facade::createKeyboardInline(static function (FactoryInterface $factory) use ($video) {
                return [
                    [
                        $factory->text(VideoEnum::DOWNLOAD, [VideoEnum::MENU => VideoEnum::DOWNLOAD, VideoEnum::ID => $video->getId()]),
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
    public static function uploadedVideo(MessageNew $data, string|Video $id): Message
    {
        return self::generate($id, static function (Video $video, string $text, ?string $preview) use ($data) {
            return (new Message())
                ->setPeerId($data->getPeerId())
                ->setMessage($text)
                ->setAttachment($video->getVkAttachment())
                ->setDontParseLinks(true);
        }, false);
    }

    /**
     * @param string|Video $idOrVideoEntity
     * @param callable $func (Video $video, string $text, ?string $preview)
     * @param bool $renderPreview
     * @return mixed
     * @throws \Exception
     */
    private static function generate(string|Video $idOrVideoEntity, callable $func, bool $renderPreview = true): mixed
    {
        if (is_object($idOrVideoEntity)) {
            $video = $idOrVideoEntity;
        } else {
            $video = Cache::get($idOrVideoEntity);
            if ($video === null) {
                $video = VideoService::get($idOrVideoEntity);
            } else {
                $video->setCached(true);
            }
        }

        $text = self::generateText($video->getAuthor(), $video->getTitle());
        $preview = $renderPreview ? self::generatePreview($video->getPreview()) : null;

        return $func($video, $text, $preview);
    }

    /**
     * @throws \Exception
     */
    private static function generatePreview(string $thumbnail_url)
    {
        return Upload::attachments(new PhotoMessages($thumbnail_url))[0];
    }

    private static function generateText(string $author, string $title): string
    {
        return "🗣 $author\n📹 $title";
    }
}