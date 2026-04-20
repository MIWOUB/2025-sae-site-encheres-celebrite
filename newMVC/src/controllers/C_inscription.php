<?php

require_once __DIR__ . '/../lib/database.php';
require_once __DIR__ . '/../model/user.php';

function inscription(array $input)
{
    // Vérifier que le formulaire a bien été soumis
    if (
        isset(
            $input['name'],
            $input['firstname'],
            $input['birth_date'],
            $input['address'],
            $input['city'],
            $input['postal_code'],
            $input['email'],
            $input['password']
        )
    ) {
        $name = trim(($input['name']));
        $firstname = trim(($input['firstname']));
        $birth_date = trim(($input['birth_date']));
        $address = trim(($input['address']));
        $city = trim(($input['city']));
        $postal_code = trim(($input['postal_code']));
        $email = trim(($input['email']));
        $password = trim((password_hash($input['password'], PASSWORD_ARGON2ID)));
    } else {
        throw new Exception("Les données du formulaire sont invalides !");
    }

    // Appel de la fonction d'incription
    $pdo = \DatabaseConnection::getConnection();
    $userRepository = new \UserRepository($pdo);
    $success = $userRepository->createUser($name, $firstname, $birth_date, $address, $city, $postal_code, $email, $password);
    if (!$success) {
        throw new Exception("Impossible de s'inscrire pour le moment !");
    } else {
        // Redirection ou message de succès
        header('Location: index.php?action=user');
    }
}
