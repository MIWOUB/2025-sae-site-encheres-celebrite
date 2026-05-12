<?php

require 'vendor/autoload.php';

use \Mailjet\Resources;

$apiKey = getenv('MAILJET_API_KEY') ?: '';
$apisecret = getenv('MAILJET_API_SECRET') ?: '';

function routeurMailing(string $action, array $param): bool
{
    switch ($action) {

        case 'sendEmailConfirmationPlublish':
            return sendEmailConfirmationPlublish($param);

        case 'InscriptionNewsletter':
            return InscriptionNewsletter($param);

        case 'InscriptionWebsite':
            return InscriptionWebsite($param);

        case 'EndAnnoncement':
            return EndAnnoncement($param);

        case 'Newsletter':
            return SendNewsletter($param);

        default:
            error_log('Action emailing non reconnue');
            return false;
    }
}

function sendEmailConfirmationPlublish(array $param): bool
{
    global $apiKey, $apisecret;

    $mj = new \Mailjet\Client($apiKey, $apisecret, true, ['version' => 'v3.1']);

    $body = [
        'Messages' => [[
            'From' => [
                'Email' => 'barthoux44@gmail.com',
                'Name' => 'Admin MaBonneEnchere'
            ],
            'To' => [[
                'Email' => $param[0],
                'Name' => $param[1]
            ]],
            'Subject' => 'Publication de votre annonce',
            'TextPart' => 'Votre annonce a été publiée avec succès.',
            'HTMLPart' => '<h3>Annonce publiée avec succès</h3>'
        ]]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);

    return $response->success();
}

function InscriptionNewsletter(array $param): bool
{
    global $apiKey, $apisecret;

    $mj = new \Mailjet\Client($apiKey, $apisecret, true, ['version' => 'v3.1']);

    $body = [
        'Messages' => [[
            'From' => [
                'Email' => 'barthoux44@gmail.com',
                'Name' => 'Admin MaBonneEnchere'
            ],
            'To' => [[
                'Email' => $param[0],
                'Name' => $param[1]
            ]],
            'Subject' => 'Newsletter inscription',
            'TextPart' => 'Bienvenue',
            'HTMLPart' => '<h3>Bienvenue dans la newsletter</h3>'
        ]]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);

    return $response->success();
}

function InscriptionWebsite(array $param): bool
{
    global $apiKey, $apisecret;

    $mj = new \Mailjet\Client($apiKey, $apisecret, true, ['version' => 'v3.1']);

    $body = [
        'Messages' => [[
            'From' => [
                'Email' => 'barthoux44@gmail.com',
                'Name' => 'Admin MaBonneEnchere'
            ],
            'To' => [[
                'Email' => $param[0],
                'Name' => $param[1]
            ]],
            'Subject' => 'Bienvenue',
            'TextPart' => 'Inscription réussie',
            'HTMLPart' => '<h3>Bienvenue sur le site</h3>'
        ]]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);

    return $response->success();
}

function EndAnnoncement(array $param): bool
{
    global $apiKey, $apisecret;

    $mj = new \Mailjet\Client($apiKey, $apisecret, true, ['version' => 'v3.1']);

    $body = [
        'Messages' => [[
            'From' => [
                'Email' => 'barthoux44@gmail.com',
                'Name' => 'Admin MaBonneEnchere'
            ],
            'To' => [[
                'Email' => $param[0],
                'Name' => $param[1]
            ]],
            'Subject' => 'Annonce terminée : ' . $param[2],
            'TextPart' => 'Votre annonce est terminée.',
            'HTMLPart' => '<h3>Annonce terminée</h3>'
        ]]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);

    return $response->success();
}

function SendNewsletter(array $param): bool
{
    global $apiKey, $apisecret;

    $mj = new \Mailjet\Client($apiKey, $apisecret, true, ['version' => 'v3.1']);

    $body = [
        'Messages' => [[
            'From' => [
                'Email' => 'barthoux44@gmail.com',
                'Name' => 'Admin MaBonneEnchere'
            ],
            'To' => [[
                'Email' => $param[0],
                'Name' => $param[1]
            ]],
            'Subject' => 'Newsletter',
            'TextPart' => $param[2],
            'HTMLPart' => '<h3>' . $param[2] . '</h3><p>' . $param[3] . '</p>'
        ]]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);

    return $response->success();
}
