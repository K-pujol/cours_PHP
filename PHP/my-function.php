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

function activerDebug(): void {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}


?>