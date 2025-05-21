<?php

function priceExcludingVAT($prix): float
{
    $TVA = 1.2;
    return $prix / $TVA;
}


function formatPrice($centimes): string
{
    $prixEuro = $centimes / 100;
    return number_format($prixEuro, 2, ',', ' ') . " €";
}

function discountedPrice($prix, $remises): string
{
    $prix = $prix * (1 - $remises / 100);
    return formatPrice((int) $prix);
}

?>