<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new UserDao());
    }

    public function getAll(): array
    {
        return array_map([$this, 'publicUser'], parent::getAll());
    }

    public function getById(int $id): array
    {
        return $this->publicUser(parent::getById($id));
    }

    public function getByEmail(string $email): array
    {
        $user = $this->dao->getByEmail($email);
        if (!$user) {
            throw new AppException('User not found', 404);
        }
        return $this->publicUser($user);
    }

    public function createUser(array $data): array
    {
        $this->requireFields($data, ['first_name', 'last_name', 'email', 'password']);
        $payload = $this->validatedPayload($data, null);
        return $this->publicUser($this->dao->insert($payload));
    }

    public function updateUser(int $id, array $data): array
    {
        parent::getById($id);
        $payload = $this->validatedPayload($data, $id);
        $this->dao->update($id, $payload);
        return $this->getById($id);
    }

    private function validatedPayload(array $data, ?int $userId): array
    {
        $data = $this->only($data, ['first_name', 'last_name', 'email', 'password', 'role', 'phone']);

        if (isset($data['first_name']) && strlen(trim($data['first_name'])) < 2) {
            throw new AppException('First name must be at least 2 characters');
        }
        if (isset($data['last_name']) && strlen(trim($data['last_name'])) < 2) {
            throw new AppException('Last name must be at least 2 characters');
        }
        if (isset($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new AppException('Invalid email format');
            }
            $existing = $this->dao->getByEmail($data['email']);
            if ($existing && (int) $existing['id'] !== (int) $userId) {
                throw new AppException('Email already exists');
            }
        }
        if (isset($data['role']) && !in_array($data['role'], ['admin', 'customer'], true)) {
            throw new AppException("Role must be 'admin' or 'customer'");
        }
        if (isset($data['password'])) {
            if (strlen($data['password']) < 8) {
                throw new AppException('Password must be at least 8 characters');
            }
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        if ($userId === null) {
            $data['role'] = $data['role'] ?? 'customer';
        }

        return $data;
    }

    public function publicUser(array $user): array
    {
        unset($user['password_hash']);
        return $user;
    }
}
