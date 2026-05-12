<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin(): bool
{
    return isset($_SESSION['user']['admin']) && (int) $_SESSION['user']['admin'] !== 0;
}

function isConnected(): bool
{
    return isset($_SESSION['user']);
}

function requireLogin(bool $expectsAjax = false): void
{
    if (isConnected()) {
        return;
    }

    if ($expectsAjax) {
        echo 'not_logged';
        exit();
    }

    header('Location: index.php?action=login');
    exit();
}

function requireAdmin(bool $expectsJson = false): void
{
    if (isAdmin()) {
        return;
    }

    if ($expectsJson) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Acces administrateur requis.']);
        exit();
    }

    if (!isConnected()) {
        header('Location: index.php?action=login');
        exit();
    }

    http_response_code(403);
    $errorMessage = 'Acces administrateur requis.';
    require __DIR__ . '/../../templates/preset/error.php';
    exit();
}
