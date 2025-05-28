<?php

function priceExcludingVAT(float $prix): float
{
    $TVA = 1.2;
    return $prix / $TVA;
}

/**
 * Formate le prix en euros avec deux décimales et un espace comme séparateur de milliers.
 *
 * @param float $centimes Le prix en centimes.
 * @return string Le prix formaté.
 */

function formatPrice(float $centimes): string
{
    $prixEuro = $centimes / 100;
    return number_format($prixEuro, 2, ',', ' ') . " €";
}

/**
 * Calcule le prix après application d'une remise.
 *
 * @param float $prix Le prix initial.
 * @param float $remises Le pourcentage de remise à appliquer.
 * @return string Le prix après remise formaté.
 */

function discountedPrice(float $prix, float $remises): string
{
    $prix = $prix * (1 - $remises / 100);
    return formatPrice((int) $prix);
}

function activerDebug(): void
{
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}



function filterForm(array $postData): array
{
    $errors = [];
    $cleanData = [];

    // ------------------------------------ Vérification du prénom ------------------------------------
    $firstName = trim($postData['first_name'] ?? '');
    $firstName = htmlspecialchars($firstName);

    if (strlen($firstName) < 2) {
        $errors[] = 'Le prénom doit contenir au moins 2 caractères.';
    }

    if (strlen($firstName) > 50) {
        $errors[] = 'Le prénom ne doit pas dépasser 50 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, tirets et espaces sont autorisés
    if (!preg_match('/^[A-ZÀ-Ÿa-zà-ÿ]+([\- ]?[A-ZÀ-Ÿa-zà-ÿ]+)*$/', $firstName)) {
        $errors[] = 'Le prénom ne doit contenir que des lettres, tirets ou espaces.';
    }

    $cleanData['first_name'] = $firstName;


    // ------------------------------------ Vérification du nom ------------------------------------
    $lastName = trim($postData['last_name'] ?? '');
    $lastName = htmlspecialchars($lastName);

    if (strlen($lastName) < 2) {
        $errors[] = 'Le nom doit contenir au moins 2 caractères.';
    }

    if (strlen($lastName) > 50) {
        $errors[] = 'Le nom ne doit pas dépasser 50 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, tirets et espaces sont autorisés
    if (!preg_match('/^[A-ZÀ-Ÿa-zà-ÿ]+([\- ]?[A-ZÀ-Ÿa-zà-ÿ]+)*$/', $lastName)) {
        $errors[] = 'Le nom ne doit contenir que des lettres, tirets ou espaces.';
    }

    $cleanData['last_name'] = $lastName;


    // ------------------------------------ Vérification de l'email ------------------------------------
    $email = trim($postData['email'] ?? '');
    $email = htmlspecialchars($email);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'L\'adresse email est invalide.';
    }
    $cleanData['email'] = $email;


    // ------------------------------------ Vérification de l'adresse ------------------------------------
    $address = trim($postData['address'] ?? '');
    $address = htmlspecialchars($address);

    if (strlen($address) < 5) {
        $errors[] = 'L\'adresse doit contenir au moins 5 caractères.';
    }

    if (strlen($address) > 100) {
        $errors[] = 'L\'adresse ne doit pas dépasser 100 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, tirets et espaces sont autorisés
    if (!preg_match('/^[0-9A-Za-zÀ-ÿ\'\,\.\- ]+$/', $address)) {
        $errors[] = 'L\'adresse ne doit contenir que des lettres, tirets ou espaces.';
    }

    $cleanData['address'] = $address;

    // ------------------------------------ Vérification du code postal ------------------------------------
    $postalCode = trim($postData['postal_code'] ?? '');
    $postalCode = htmlspecialchars($postalCode);

    if (strlen($postalCode) < 5 || strlen($postalCode) > 5) {
        $errors[] = 'Le code postal doit contenir exactement 5 chiffres.';
    }

    // Vérification des caractères autorisés. Seuls les chiffres sont autorisés
    if (!preg_match('/^\d+$/', $postalCode)) {
        $errors[] = 'Le code postal ne doit contenir que des chiffres.';
    }

    $cleanData['postal_code'] = $postalCode;


    // ------------------------------------ Vérification de la ville ------------------------------------
    $city = trim($postData['city'] ?? '');
    $city = htmlspecialchars($city);

    if (strlen($city) < 2) {
        $errors[] = 'La ville doit contenir au moins 2 caractères.';
    }

    if (strlen($city) > 50) {
        $errors[] = 'La ville ne doit pas dépasser 50 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, tirets et espaces sont autorisés
    if (!preg_match('/^[A-ZÀ-Ÿa-zà-ÿ]+([\- ]?[A-ZÀ-Ÿa-zà-ÿ]+)*$/', $city)) {
        $errors[] = 'La ville ne doit contenir que des lettres, tirets ou espaces.';
    }

    $cleanData['city'] = $city;


    // Vérification de présence des champs requis
    if (empty($cleanData['first_name']))   $errors[] = 'Le prénom est requis.';
    if (empty($cleanData['last_name']))    $errors[] = 'Le nom est requis.';
    if (!$cleanData['email'])              $errors[] = 'Adresse email invalide.';
    if (empty($cleanData['address']))      $errors[] = 'L’adresse est requise.';
    if (empty($cleanData['postal_code']))  $errors[] = 'Le code postal est requis.';
    if (empty($cleanData['city']))         $errors[] = 'La ville est requise.';

    return ['data' => $cleanData, 'errors' => $errors];
}
