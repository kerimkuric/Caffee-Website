<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class CategoryService extends BaseService
{
    private ProductDao $productDao;

    public function __construct()
    {
        parent::__construct(new CategoryDao());
        $this->productDao = new ProductDao();
    }

    public function delete(int $id): bool
    {
        $this->getById($id);
        if ($this->productDao->getByCategoryId($id) !== []) {
            throw new AppException('Cannot delete a category that still has products', 400);
        }
        return $this->dao->delete($id);
    }

    public function createCategory(array $data): array
    {
        $this->requireFields($data, ['name']);
        return $this->dao->insert($this->validatedPayload($data, null));
    }

    public function updateCategory(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $this->validatedPayload($data, $id));
        return $this->getById($id);
    }

    private function validatedPayload(array $data, ?int $id): array
    {
        $data = $this->only($data, ['name', 'description']);
        if (isset($data['name'])) {
            $name = trim($data['name']);
            if ($name === '') {
                throw new AppException('Category name is required');
            }
            $existing = $this->dao->getByName($name);
            if ($existing && (int) $existing['id'] !== (int) $id) {
                throw new AppException('Category name already exists');
            }
            $data['name'] = $name;
        }
        return $data;
    }
}
