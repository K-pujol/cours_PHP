<?php

function priceExcludingVAT(float $prix): float
{
    $TVA = 1.2;
    return $prix / $TVA;
}


function formatPrice(float $centimes): string
{
    $prixEuro = $centimes / 100;
    return number_format($prixEuro, 2, ',', ' ') . " €";
}

function discountedPrice(float $prix, float $remises): string
{
    $prix = $prix * (1 - $remises / 100);
    return formatPrice((int) $prix);
}

?>