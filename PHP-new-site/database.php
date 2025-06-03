<?php


function sendDataToDatabase(string $sqlQuery, array $params = [])
{
    try {
        $mysqlClient = new PDO(
            'mysql:host=localhost;dbname=2025_05_28_sqlTartes;charset=utf8',
            'business',
            'motdepasse'
        );
        $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $statement = $mysqlClient->prepare($sqlQuery);
    $statement->execute($params);

    $queryType = strtoupper(strtok(trim($sqlQuery), " "));

    if ($queryType === 'INSERT') {
        return $mysqlClient->lastInsertId(); // retourne l'ID inséré
    } elseif ($queryType === 'SELECT') {
        return $statement->fetchAll(PDO::FETCH_ASSOC); // retourne les données
    } else {
        return true; // pour UPDATE, DELETE, etc.
    }
}

function getAllProducts(): array
{
    return sendDataToDatabase('SELECT * FROM products');
}


function lastTenDaysOrder(): array
{
    return sendDataToDatabase('SELECT * FROM orders WHERE create_at >= NOW() - INTERVAL 10 DAY');
}

function showProductsByOrder(int $order): array
{
    $sqlQuery = "SELECT name, order_items.quantity, price 
        FROM products 
        JOIN order_items 
        ON products.id = order_items.product_id
        AND order_items.order_id = :orderId";

    return sendDataToDatabase($sqlQuery, [':orderId' => $order]);
}

function amountOrderByCustomer(int $customerId): array
{
    $sqlQuery = "SELECT customers.first_name, customers.last_name, SUM(order_items.quantity * products.price) AS total_amount
        FROM customers
        LEFT JOIN orders ON orders.customer_id = customers.id
        LEFT JOIN order_items ON order_items.order_id = orders.id
        LEFT JOIN products ON products.id = order_items.product_id
        WHERE customers.id = :customerId
        GROUP BY customers.first_name, customers.last_name";

    return sendDataToDatabase($sqlQuery, [':customerId' => $customerId]);
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
): int {
    $sqlQuery = "INSERT INTO products 
        (category_id, vat_id, name, description, price, url_image, weight, quantity, is_available) 
        VALUES (:categoryId, :vatId, :name, :description, :price, :urlImage, :weight, :quantity, :isAvailable)";

    $params = [
        ':categoryId' => $categoryId,
        ':vatId' => $vatId,
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':urlImage' => $urlImage,
        ':weight' => $weight,
        ':quantity' => $quantity,
        ':isAvailable' => $isAvailable,
    ];

    return sendDataToDatabase($sqlQuery, $params);
}

function addCustomers(
    string $firstName,
    string $lastName,
    string $email,
    string $address,
    int $postalCode,
    string $city
): int {
    $sqlQuery = "INSERT INTO customers 
        (first_name, last_name, email, address, postal_code, city) 
        VALUES (:firstName, :lastName, :email, :address, :postalCode, :city)";

    return sendDataToDatabase($sqlQuery, [
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':email' => $email,
        ':address' => $address,
        ':postalCode' => $postalCode,
        ':city' => $city
    ]);
}

function createOrders(
    int $customerId = 1,
    string $discountCode = '',
    int $deliveryMode = 0,
    int $totalAmount = 0
): int {
    $sqlQuery = "INSERT INTO orders 
        (customer_id, discount_name, delivery_mode_id, total_amount) 
        VALUES (:customerId, :discountCode, :deliveryMode, :totalAmount)";

    $params = [
        ':customerId' => $customerId,
        ':discountCode' => $discountCode !== '' ? $discountCode : null,
        ':deliveryMode' => $deliveryMode,
        ':totalAmount' => $totalAmount
    ];

    return sendDataToDatabase($sqlQuery, $params);
}

function createOrderItems(
    int $orderId,
    int $productId,
    int $quantity,
    float $price
): int {
    $sqlQuery = "INSERT INTO order_items
        (order_id, product_id, quantity, price) 
        VALUES (:orderId, :productId, :quantity, :price)";

    return sendDataToDatabase($sqlQuery, [
        ':orderId' => $orderId,
        ':productId' => $productId,
        ':quantity' => $quantity,
        ':price' => $price
    ]);
}
