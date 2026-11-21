<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class OrderService extends BaseService
{
    private OrderItemDao $orderItemDao;
    private UserDao $userDao;
    private ProductDao $productDao;

    public function __construct()
    {
        parent::__construct(new OrderDao());
        $this->orderItemDao = new OrderItemDao();
        $this->userDao = new UserDao();
        $this->productDao = new ProductDao();
    }

    public function getByUserId(int $userId): array
    {
        if (!$this->userDao->getById($userId)) {
            throw new AppException('User not found', 404);
        }
        return $this->dao->getByUserId($userId);
    }

    public function getWithItems(int $id): array
    {
        $order = $this->getById($id);
        $order['items'] = $this->orderItemDao->getByOrderId($id);
        return $order;
    }

    public function createOrder(array $data): array
    {
        $this->requireFields($data, ['user_id']);
        $userId = $this->requireInt($data['user_id'], 'user_id');
        if (!$this->userDao->getById($userId)) {
            throw new AppException('User not found', 400);
        }

        $status = $data['status'] ?? 'pending';
        if (!in_array($status, ['pending', 'preparing', 'ready', 'completed', 'cancelled'], true)) {
            throw new AppException('Invalid order status');
        }

        if (!empty($data['items']) && is_array($data['items'])) {
            $items = $this->normalizeItems($data['items']);
            $total = 0;
            foreach ($items as $item) {
                $total += $item['quantity'] * (float) $item['unit_price'];
            }
            $order = $this->dao->createWithItems(
                [
                    'user_id' => $userId,
                    'status' => $status,
                    'total' => number_format($total, 2, '.', ''),
                ],
                $items
            );
            return $this->getWithItems((int) $order['id']);
        }

        $total = isset($data['total']) ? (float) $data['total'] : 0;
        if ($total < 0) {
            throw new AppException('total must be 0 or greater');
        }

        $order = $this->dao->insert([
            'user_id' => $userId,
            'status' => $status,
            'total' => number_format($total, 2, '.', ''),
        ]);
        return $this->getById((int) $order['id']);
    }

    public function updateOrder(int $id, array $data): array
    {
        $this->getById($id);
        $payload = $this->only($data, ['status', 'total']);
        if (isset($payload['status']) && !in_array($payload['status'], ['pending', 'preparing', 'ready', 'completed', 'cancelled'], true)) {
            throw new AppException('Invalid order status');
        }
        if (isset($payload['total'])) {
            if (!is_numeric($payload['total']) || (float) $payload['total'] < 0) {
                throw new AppException('total must be 0 or greater');
            }
            $payload['total'] = number_format((float) $payload['total'], 2, '.', '');
        }
        $this->dao->update($id, $payload);
        return $this->getById($id);
    }

    private function normalizeItems(array $items): array
    {
        $normalized = [];
        foreach ($items as $item) {
            if (empty($item['product_id']) || empty($item['quantity'])) {
                throw new AppException('Each item needs product_id and quantity');
            }
            $productId = $this->requireInt($item['product_id'], 'product_id');
            $quantity = (int) $item['quantity'];
            if ($quantity < 1) {
                throw new AppException('quantity must be at least 1');
            }
            $product = $this->productDao->getById($productId);
            if (!$product) {
                throw new AppException("Product $productId not found", 400);
            }
            if (!(int) $product['is_available']) {
                throw new AppException($product['name'] . ' is not available');
            }
            $normalized[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $product['price'],
            ];
        }
        if ($normalized === []) {
            throw new AppException('Order must contain at least one item');
        }
        return $normalized;
    }
}
