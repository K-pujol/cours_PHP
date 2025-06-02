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



function filterFormCustomer(array $postData): array
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


function filterFormProduct(array $postData): array
{
    $errors = [];
    $cleanData = [];

    // ------------------------------------ Vérification de la catégorie ------------------------------------
    $categoryId = trim($postData['category_id'] ?? '');
    $categoryId = htmlspecialchars($categoryId);

    $validCategories = ['1', '2', '3', '4', '5'];
    if (!in_array($categoryId, $validCategories, true)) {
        $errors[] = 'La catégorie est invalide.';
    }
    $cleanData['category_id'] = $categoryId;

    // ------------------------------------ Vérification de la TVA ------------------------------------
    $vatId = trim($postData['vat_id'] ?? '');
    $vatId = htmlspecialchars($vatId);

    $validVats = ['1', '2', '3'];
    if (!in_array($vatId, $validVats, true)) {
        $errors[] = 'La TVA est invalide.';
    }
    $cleanData['vat_id'] = $vatId;

    // ------------------------------------ Vérification du nom ------------------------------------
    $productName = trim($postData['product_name'] ?? '');
    $productName = htmlspecialchars($productName);

    if (strlen($productName) < 2) {
        $errors[] = 'Le nom du produit doit contenir au moins 2 caractères.';
    }

    if (strlen($productName) > 50) {
        $errors[] = 'Le nom du produit ne doit pas dépasser 50 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, tirets et espaces sont autorisés
    if (!preg_match('/^[A-ZÀ-Ÿa-zà-ÿ]+([\- ]?[A-ZÀ-Ÿa-zà-ÿ]+)*$/', $productName)) {
        $errors[] = 'Le nom du produit ne doit contenir que des lettres, tirets ou espaces.';
    }

    $cleanData['product_name'] = $productName;
    // ------------------------------------ Vérification de la description ------------------------------------
    $description = trim($postData['description'] ?? '');
    $description = htmlspecialchars($description);

    if (strlen($description) < 5) {
        $errors[] = 'La description doit contenir au moins 5 caractères.';
    }

    if (strlen($description) > 500) {
        $errors[] = 'La description ne doit pas dépasser 500 caractères.';
    }

    // Vérification des caractères autorisés. Seuls les lettres, chiffres, tirets et espaces sont autorisés
    if (!preg_match('/^[0-9A-Za-zÀ-ÿ\'\,\.\- ]+$/', $description)) {
        $errors[] = 'La description ne doit contenir que des lettres, chiffres, tirets ou espaces.';
    }

    $cleanData['description'] = $description;
    // ------------------------------------ Vérification du prix ------------------------------------
    $price = trim($postData['price'] ?? '');
    $price = htmlspecialchars($price);
    if (!is_numeric($price) || $price <= 0) {
        $errors[] = 'Le prix doit être un nombre positif.';
    }
    $price = floatval($price);
    if ($price < 0.01 || $price > 10000) {
        $errors[] = 'Le prix doit être compris entre 0,01 € et 10 000,00 €.';
    } else {
        $cleanData['price'] = $price;
    }


    // ------------------------------------ Vérification de l'URL de l'image ------------------------------------
    $urlImage = trim($postData['url_image'] ?? '');
    $urlImage = htmlspecialchars($urlImage);
    if (!filter_var($urlImage, FILTER_VALIDATE_URL)) {
        $errors[] = 'L\'URL de l\'image est invalide.';
    } else {
        $cleanData['url_image'] = $urlImage;
    }
    // ------------------------------------ Vérification du poids ------------------------------------
    $weight = trim($postData['weight'] ?? '');
    $weight = htmlspecialchars($weight);
    if (!is_numeric($weight) || $weight <= 0) {
        $errors[] = 'Le poids doit être un nombre positif.';
    }
    $weight = floatval($weight);
    if ($weight < 0.01 || $weight > 10000) {
        $errors[] = 'Le poids doit être compris entre 0,01 g et 10 000,00 g.';
    } else {
        $cleanData['weight'] = $weight;
    }
    // ------------------------------------ Vérification de la quantité en stock ------------------------------------
    $quantity = trim($postData['quantity'] ?? '');
    $quantity = htmlspecialchars($quantity);
    if (!is_numeric($quantity) || $quantity < 0) {
        $errors[] = 'La quantité doit être un nombre positif ou zéro.';
    }
    $quantity = intval($quantity);
    if ($quantity < 0 || $quantity > 10000) {
        $errors[] = 'La quantité doit être comprise entre 0 et 10 000.';
    } else {
        $cleanData['quantity'] = $quantity;
    }
    // ------------------------------------ Vérification de la disponibilité ------------------------------------
    $isAvailable = isset($postData['is_available']) ? 1 : 0;
    $cleanData['is_available'] = $isAvailable;
    // ------------------------------------ Vérification de présence des champs requis ------------------------------------
    if (!isset($cleanData['category_id']) || $cleanData['category_id'] === '') $errors[] = 'La catégorie est requise.';
    if (!isset($cleanData['vat_id']) || $cleanData['vat_id'] === '') $errors[] = 'La TVA est requise.';
    if (!isset($cleanData['product_name']) || $cleanData['product_name'] === '') $errors[] = 'Le nom du produit est requis.';
    if (!isset($cleanData['description']) || $cleanData['description'] === '') $errors[] = 'La description est requise.';
    if (!isset($cleanData['price'])) $errors[] = 'Le prix est requis.';
    if (!isset($cleanData['url_image']) || $cleanData['url_image'] === '') $errors[] = 'L\'URL de l\'image est requise.';
    if (!isset($cleanData['weight'])) $errors[] = 'Le poids est requis.';
    if (!isset($cleanData['quantity'])) $errors[] = 'La quantité est requise.';

    return ['data' => $cleanData, 'errors' => $errors];
}
