<?php
require_once __DIR__ . '/dao/UserDao.php';
require_once __DIR__ . '/dao/CategoryDao.php';
require_once __DIR__ . '/dao/ProductDao.php';
require_once __DIR__ . '/dao/ReservationDao.php';
require_once __DIR__ . '/dao/OrderDao.php';
require_once __DIR__ . '/dao/OrderItemDao.php';
require_once __DIR__ . '/dao/ReviewDao.php';

function assertTrue(bool $ok, string $label): void
{
    echo ($ok ? 'PASSED' : 'FAILED') . "  $label\n";
    if (!$ok) {
        exit(1);
    }
}

try {
    $users = new UserDao();
    $categories = new CategoryDao();
    $products = new ProductDao();
    $reservations = new ReservationDao();
    $orders = new OrderDao();
    $orderItems = new OrderItemDao();
    $reviews = new ReviewDao();

    echo "Read sample data\n";
    assertTrue($users->getByEmail('admin@kodkere.ba') !== null, 'UserDao getByEmail');
    assertTrue(count($categories->getAll()) >= 3, 'CategoryDao getAll');
    assertTrue($products->getById(1) !== null, 'ProductDao getById');
    assertTrue(count($reservations->getByUserId(2)) >= 1, 'ReservationDao getByUserId');
    assertTrue(count($orders->getByUserId(2)) >= 1, 'OrderDao getByUserId');
    assertTrue(count($orderItems->getByOrderId(1)) >= 1, 'OrderItemDao getByOrderId');
    assertTrue(count($reviews->getByProductId(2)) >= 1, 'ReviewDao getByProductId');

    echo "\nCRUD on categories\n";
    $created = $categories->insert([
        'name' => 'Test category',
        'description' => 'Temporary row for DAO test',
    ]);
    assertTrue(isset($created['id']), 'CategoryDao insert');
    $row = $categories->getById($created['id']);
    assertTrue($row !== null && $row['name'] === 'Test category', 'CategoryDao getById after insert');
    $updated = $categories->update($created['id'], ['description' => 'Updated']);
    assertTrue($updated, 'CategoryDao update');
    $deleted = $categories->delete($created['id']);
    assertTrue($deleted, 'CategoryDao delete');

    echo "\nAll DAO tests completed.\n";
} catch (Throwable $e) {
    echo 'Test failed: ' . $e->getMessage() . "\n";
    exit(1);
}
