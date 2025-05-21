<?php

function calculerPrixHT($prix): float
{
    $TVA = 0.2;
    return $prix / (1 + $TVA);
}

function formatPrice($centimes): string
{
    $prixEuro = $centimes / 100;
    return number_format($prixEuro, 2, ',', ' ') . " €";
}

?>