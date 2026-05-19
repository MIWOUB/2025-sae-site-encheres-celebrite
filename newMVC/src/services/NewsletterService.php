<?php

require_once __DIR__ . '/EmailingService.php';

class NewsletterService
{
    private $emailingService;

    public function __construct($emailingService = null)
    {
        $this->emailingService = $emailingService ?? new \EmailingService();
    }

    public function sendToSubscribers(array $subscribers, string $title, string $content): int
    {
        $sentCount = 0;

        foreach ($subscribers as $user) {
            if (empty($user['email']) || empty($user['name'])) {
                continue;
            }

            $wasSent = $this->emailingService->sendNewsletter($user['email'], $user['name'], $title, $content);

            if ($wasSent) {
                $sentCount++;
            }
        }

        return $sentCount;
    }
}
