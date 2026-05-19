<?php

use Mailjet\Client;
use Mailjet\Resources;

class EmailingService
{
    private string $apiKey;
    private string $apiSecret;
    private string $fromEmail;
    private string $fromName;

    public function __construct(?string $apiKey = null, ?string $apiSecret = null, ?string $fromEmail = null, ?string $fromName = null)
    {
        $this->apiKey = $apiKey ?? getenv('MAILJET_API_KEY') ?: '';
        $this->apiSecret = $apiSecret ?? getenv('MAILJET_API_SECRET') ?: '';
        $this->fromEmail = $fromEmail ?? getenv('MAIL_FROM_EMAIL') ?: 'barthoux44@gmail.com';
        $this->fromName = $fromName ?? getenv('MAIL_FROM_NAME') ?: 'Admin MaBonneEnchere';
    }

    private function client(): Client
    {
        return new Client($this->apiKey, $this->apiSecret, true, ['version' => 'v3.1']);
    }

    private function sendMessage(array $to, string $subject, string $text, string $html): bool
    {
        $mj = $this->client();

        $body = [
            'Messages' => [[
                'From' => [
                    'Email' => $this->fromEmail,
                    'Name' => $this->fromName
                ],
                'To' => [$to],
                'Subject' => $subject,
                'TextPart' => $text,
                'HTMLPart' => $html
            ]]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
        return $response->success();
    }

    public function sendEmailConfirmationPublish(string $email, string $name): bool
    {
        return $this->sendMessage(
            ['Email' => $email, 'Name' => $name],
            'Publication de votre annonce',
            'Votre annonce a été publiée avec succès.',
            '<h3>Annonce publiée avec succès</h3>'
        );
    }

    public function subscribeNewsletter(string $email, string $name): bool
    {
        return $this->sendMessage(
            ['Email' => $email, 'Name' => $name],
            'Newsletter inscription',
            'Bienvenue',
            '<h3>Bienvenue dans la newsletter</h3>'
        );
    }

    public function sendWelcomeWebsite(string $email, string $name): bool
    {
        return $this->sendMessage(
            ['Email' => $email, 'Name' => $name],
            'Bienvenue',
            'Inscription réussie',
            '<h3>Bienvenue sur le site</h3>'
        );
    }

    public function notifyEndAnnouncement(string $email, string $name, string $adTitle): bool
    {
        return $this->sendMessage(
            ['Email' => $email, 'Name' => $name],
            'Annonce terminée : ' . $adTitle,
            'Votre annonce est terminée.',
            '<h3>Annonce terminée</h3>'
        );
    }

    public function sendNewsletter(string $email, string $name, string $title, string $content): bool
    {
        $html = '<h3>' . htmlspecialchars($title) . '</h3><p>' . nl2br(htmlspecialchars($content)) . '</p>';
        return $this->sendMessage(
            ['Email' => $email, 'Name' => $name],
            'Newsletter',
            $title,
            $html
        );
    }
}
