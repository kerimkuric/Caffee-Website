<?php
require_once __DIR__ . '/BaseDao.php';

class OrderItemDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('order_items');
    }

    public function getByOrderId(int $orderId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT oi.*, p.name AS product_name
             FROM order_items oi
             INNER JOIN products p ON p.id = oi.product_id
             WHERE oi.order_id = :order_id'
        );
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll();
    }
}
