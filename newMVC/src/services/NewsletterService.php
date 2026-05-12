<?php

require_once __DIR__ . '/../controllers/EmailingController.php';

class NewsletterService
{
    public function sendToSubscribers(array $subscribers, string $title, string $content): int
    {
        $sentCount = 0;

        foreach ($subscribers as $user) {
            if (empty($user['email']) || empty($user['name'])) {
                continue;
            }

            $params = [$user['email'], $user['name'], $title, $content];
            $wasSent = routeurMailing('Newsletter', $params);

            if ($wasSent) {
                $sentCount++;
            }
        }

        return $sentCount;
    }
}
