<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';
require_once __DIR__ . '/../dao/OrderDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class OrderItemService extends BaseService
{
    private OrderDao $orderDao;
    private ProductDao $productDao;

    public function __construct()
    {
        parent::__construct(new OrderItemDao());
        $this->orderDao = new OrderDao();
        $this->productDao = new ProductDao();
    }

    public function getByOrderId(int $orderId): array
    {
        if (!$this->orderDao->getById($orderId)) {
            throw new AppException('Order not found', 404);
        }
        return $this->dao->getByOrderId($orderId);
    }

    public function createItem(array $data): array
    {
        $this->requireFields($data, ['order_id', 'product_id', 'quantity']);
        return $this->dao->insert($this->validatedPayload($data, true));
    }

    public function updateItem(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $this->validatedPayload($data, false));
        return $this->getById($id);
    }

    private function validatedPayload(array $data, bool $creating): array
    {
        $data = $this->only($data, ['order_id', 'product_id', 'quantity', 'unit_price']);
        if (isset($data['order_id'])) {
            $data['order_id'] = $this->requireInt($data['order_id'], 'order_id');
            if (!$this->orderDao->getById($data['order_id'])) {
                throw new AppException('Order not found', 400);
            }
        }
        if (isset($data['product_id'])) {
            $data['product_id'] = $this->requireInt($data['product_id'], 'product_id');
            $product = $this->productDao->getById($data['product_id']);
            if (!$product) {
                throw new AppException('Product not found', 400);
            }
            if ($creating && !isset($data['unit_price'])) {
                $data['unit_price'] = $product['price'];
            }
        }
        if (isset($data['quantity'])) {
            $qty = (int) $data['quantity'];
            if ($qty < 1) {
                throw new AppException('quantity must be at least 1');
            }
            $data['quantity'] = $qty;
        }
        return $data;
    }
}
