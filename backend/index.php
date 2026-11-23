<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Authentication, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

require_once __DIR__ . '/services/AppException.php';
require_once __DIR__ . '/services/Logger.php';
require_once __DIR__ . '/services/BaseService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/CategoryService.php';
require_once __DIR__ . '/services/ProductService.php';
require_once __DIR__ . '/services/ReservationService.php';
require_once __DIR__ . '/services/OrderService.php';
require_once __DIR__ . '/services/OrderItemService.php';
require_once __DIR__ . '/services/ReviewService.php';
require_once __DIR__ . '/services/LocationService.php';

Flight::register('userService', 'UserService');
Flight::register('categoryService', 'CategoryService');
Flight::register('productService', 'ProductService');
Flight::register('reservationService', 'ReservationService');
Flight::register('orderService', 'OrderService');
Flight::register('orderItemService', 'OrderItemService');
Flight::register('reviewService', 'ReviewService');
Flight::register('locationService', 'LocationService');

require_once __DIR__ . '/routes/helpers.php';
require_once __DIR__ . '/routes/user_routes.php';
require_once __DIR__ . '/routes/category_routes.php';
require_once __DIR__ . '/routes/product_routes.php';
require_once __DIR__ . '/routes/reservation_routes.php';
require_once __DIR__ . '/routes/order_routes.php';
require_once __DIR__ . '/routes/order_item_routes.php';
require_once __DIR__ . '/routes/review_routes.php';
require_once __DIR__ . '/routes/location_routes.php';

Flight::route('GET /', function () {
    Flight::redirect('/public/v1/docs/');
});

Flight::map('error', function (Throwable $ex) {
    if ($ex instanceof AppException) {
        Flight::json([
            'success' => false,
            'message' => $ex->getMessage(),
        ], $ex->getStatus());
        return;
    }
    Logger::error($ex->getMessage());
    Flight::json([
        'success' => false,
        'message' => 'Server error',
    ], 500);
});

Flight::map('notFound', function () {
    Flight::json([
        'success' => false,
        'message' => 'Endpoint not found',
    ], 404);
});

Flight::start();
