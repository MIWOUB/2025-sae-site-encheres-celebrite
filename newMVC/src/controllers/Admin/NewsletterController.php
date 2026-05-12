<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../lib/auth.php';
require_once __DIR__ . '/../../model/user.php';
require_once __DIR__ . '/../../services/NewsletterService.php';

class NewsletterController
{
    public function postNewsletter(array $input): void
    {
        if (!isAdmin()) {
            throw new Exception('Acces administrateur requis.');
        }

        $title = trim((string) ($input['title_news'] ?? ''));
        $content = trim((string) ($input['content_mail_newsletter'] ?? ''));

        if ($title === '' || $content === '') {
            throw new Exception('Titre et contenu de la newsletter requis.');
        }

        $pdo = \DatabaseConnection::getConnection();
        $userRepository = new \UserRepository($pdo);
        $subscribers = $userRepository->getUserNewsletter();

        $newsletterService = new \NewsletterService();
        $newsletterService->sendToSubscribers($subscribers, $title, $content);

        redirectTo('index.php?action=admin');
    }
}
