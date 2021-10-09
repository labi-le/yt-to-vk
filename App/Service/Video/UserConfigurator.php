<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\UserData;
use Astaroth\Support\Facades\Entity;
use Astaroth\Support\Facades\Request;

class UserConfigurator
{
    /**
     * @throws InvalidAccessTokenException
     */
    public static function setAccessToken(UserData $userEntity, bool $validate = true): void
    {
        if ($validate) {
            self::checkToken($userEntity->getAccessToken());
        }

        $entity = new Entity();

        /** @var UserData|null $userRepository */
        $userRepository = $entity->getRepository(UserData::class)->find($userEntity->getId());
        if ($userRepository === null) {
            $entity->persist($userEntity);
        } else {
            $userRepository->setAccessToken($userEntity->getAccessToken());
        }

        $entity->flush();
    }

    /**
     * @throws InvalidAccessTokenException
     */
    private static function checkToken(string $access_token): void
    {
        try {
            Request::call("users.get", token: $access_token);
        } catch (\Throwable) {
            throw new InvalidAccessTokenException("Invalid access token");
        }
    }

    /**
     * @throws MissingAccessTokenException|InvalidAccessTokenException
     */
    public static function getAccessToken(UserData $userData): string
    {
        $em = new Entity;
        $userRepository = $em->find(UserData::class, $userData->getId());

        if ($userRepository === null || $userRepository->getAccessToken() === null) {
            throw new MissingAccessTokenException("Не установлен access token для загрузки видео");
        }

        try {
            self::checkToken($userRepository->getAccessToken());
        } catch (InvalidAccessTokenException) {
            self::setAccessToken(
                $userData->setAccessToken(null)
            );
            throw new InvalidAccessTokenException("Токен устарел и нуждается в обновлении");
        }

        return $userRepository->getAccessToken();
    }
}