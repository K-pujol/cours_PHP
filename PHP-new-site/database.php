<?php


function sendDataToDatabase(string $sqlQuery)
{

    try {
        $mysqlClient = new PDO(
            'mysql:host=localhost;dbname=2025_05_28_sqlTartes;charset=utf8',
            'business',
            'motdepasse'
        );
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $answer = $mysqlClient->prepare($sqlQuery);
    $answer->execute();

    return $answer->fetchAll(PDO::FETCH_ASSOC);
}


function getAllProducts(): array
{
    $sqlQuery = 'SELECT * FROM products';
    return sendDataToDatabase($sqlQuery);
}


function lastTenDaysOrder(): array
{
    $sqlQuery = 'SELECT * FROM  orders WHERE date >= NOW() - INTERVAL 10 DAY';
    return sendDataToDatabase($sqlQuery);
}


function showProductsByOrder(int $order): array
{
    $sqlQuery =
        "SELECT name, order_product.quantity, price 
    FROM products 
    JOIN order_product 
    ON products.id = order_product.product_id 
    AND order_product.order_id = $order";

    return sendDataToDatabase($sqlQuery);
}

function amountOrderByCustomer(int $customerId): array
{
    $sqlQuery = "SELECT customers.first_name, customers.last_name, SUM(order_product.quantity * products.price) AS total_amount
    FROM customers
    LEFT JOIN orders ON orders.customer_id = customers.id
    LEFT JOIN order_product ON order_product.order_id = orders.id
    LEFT JOIN products ON products.id = order_product.product_id
    WHERE customers.id = $customerId
    GROUP BY customers.first_name, customers.last_name";

    return sendDataToDatabase($sqlQuery);
}


function addProducts(
    int $categoryId,
    int $vatId,
    string $name,
    string $description,
    float $price,
    string $urlImage,
    float $weight,
    int $quantity,
    int $isAvailable = 1
): array {

    $sqlQuery = "INSERT INTO `products` 
    (`category_id`, `vat_id`, `name`, `description`, `price`, `url_image`, `weight`, `quantity`, `is_available`) 
    VALUES ($categoryId, $vatId, '$name', '$description', $price, '$urlImage', $weight, $quantity, $isAvailable);";

    return sendDataToDatabase($sqlQuery);
}

function addCustomers(
    string $firstName,
    string $lastName,
    string $email,
    string $address,
    int $postalCode,
    string $city
): array {
    $sqlQuery = "INSERT INTO `customers` 
    (`first_name`, `last_name`, `email`, `address`, `postal_code`, `city`) 
    VALUES ('$firstName', '$lastName', '$email', '$address', '$postalCode', '$city');";

    return sendDataToDatabase($sqlQuery);
}
