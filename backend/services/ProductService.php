<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/OrderItemDao.php';

class ProductService extends BaseService
{
    private CategoryDao $categoryDao;
    private OrderItemDao $orderItemDao;

    public function __construct()
    {
        parent::__construct(new ProductDao());
        $this->categoryDao = new CategoryDao();
        $this->orderItemDao = new OrderItemDao();
    }

    public function delete(int $id): bool
    {
        $this->getById($id);
        if ($this->orderItemDao->getByProductId($id) !== []) {
            throw new AppException('Cannot delete a product that appears on orders', 400);
        }
        return $this->dao->delete($id);
    }

    public function getByCategoryId(int $categoryId): array
    {
        if (!$this->categoryDao->getById($categoryId)) {
            throw new AppException('Category not found', 404);
        }
        return $this->dao->getByCategoryId($categoryId);
    }

    public function createProduct(array $data): array
    {
        $this->requireFields($data, ['category_id', 'name', 'price']);
        return $this->dao->insert($this->validatedPayload($data, true));
    }

    public function updateProduct(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $this->validatedPayload($data, false));
        return $this->getById($id);
    }

    private function validatedPayload(array $data, bool $creating): array
    {
        $data = $this->only($data, ['category_id', 'name', 'description', 'price', 'image_url', 'is_available']);
        if (isset($data['category_id'])) {
            $data['category_id'] = $this->requireInt($data['category_id'], 'category_id');
            if (!$this->categoryDao->getById($data['category_id'])) {
                throw new AppException('Category not found', 400);
            }
        }
        if (isset($data['name']) && strlen(trim($data['name'])) < 2) {
            throw new AppException('Product name must be at least 2 characters');
        }
        if (isset($data['price'])) {
            if (!is_numeric($data['price']) || (float) $data['price'] < 0) {
                throw new AppException('Price must be a number greater than or equal to 0');
            }
            $data['price'] = number_format((float) $data['price'], 2, '.', '');
        }
        if (isset($data['is_available'])) {
            $data['is_available'] = $data['is_available'] ? 1 : 0;
        } elseif ($creating) {
            $data['is_available'] = 1;
        }
        return $data;
    }
}
