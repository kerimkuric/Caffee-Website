<?php
require_once __DIR__ . '/BaseDao.php';

class OrderDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('orders');
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function createWithItems(array $orderData, array $items): array
    {
        try {
            $this->connection->beginTransaction();
            $order = $this->insert($orderData);
            $stmt = $this->connection->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price)
                 VALUES (:order_id, :product_id, :quantity, :unit_price)'
            );
            foreach ($items as $item) {
                $stmt->execute([
                    'order_id'     => $order['id'],
                    'product_id'   => $item['product_id'],
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                ]);
            }
            $this->connection->commit();
            return $order;
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
