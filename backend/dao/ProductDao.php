<?php
require_once __DIR__ . '/BaseDao.php';

class ProductDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('products');
    }

    public function getByCategoryId(int $categoryId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM products WHERE category_id = :category_id ORDER BY name'
        );
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function getAvailable(): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM products WHERE is_available = 1 ORDER BY name'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
