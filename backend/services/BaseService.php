<?php
require_once __DIR__ . '/../dao/BaseDao.php';
require_once __DIR__ . '/AppException.php';

class BaseService
{
    public function __construct(protected BaseDao $dao)
    {
    }

    public function getAll(): array
    {
        return $this->dao->getAll();
    }

    public function getById(int $id): array
    {
        $row = $this->dao->getById($id);
        if (!$row) {
            throw new AppException('Resource not found', 404);
        }
        return $row;
    }

    public function create(array $data): array
    {
        return $this->dao->insert($data);
    }

    public function update(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $data);
        return $this->dao->getById($id);
    }

    public function delete(int $id): bool
    {
        $this->getById($id);
        return $this->dao->delete($id);
    }

    protected function requireFields(array $data, array $fields): void
    {
        foreach ($fields as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new AppException("Field '$field' is required");
            }
        }
    }

    protected function only(array $data, array $allowed): array
    {
        $filtered = array_intersect_key($data, array_flip($allowed));
        if ($filtered === []) {
            throw new AppException('No valid fields provided');
        }
        return $filtered;
    }

    protected function requireInt($value, string $field): int
    {
        if (!is_numeric($value) || (int) $value < 1) {
            throw new AppException("Field '$field' must be a positive integer");
        }
        return (int) $value;
    }
}
