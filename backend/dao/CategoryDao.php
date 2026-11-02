<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('categories');
    }

    public function getByName(string $name): ?array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM categories WHERE name = :name'
        );
        $stmt->execute(['name' => $name]);
        return $stmt->fetch() ?: null;
    }
}
