<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReservationDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class ReservationService extends BaseService
{
    private UserDao $userDao;

    public function __construct()
    {
        parent::__construct(new ReservationDao());
        $this->userDao = new UserDao();
    }

    public function getByUserId(int $userId): array
    {
        if (!$this->userDao->getById($userId)) {
            throw new AppException('User not found', 404);
        }
        return $this->dao->getByUserId($userId);
    }

    public function createReservation(array $data): array
    {
        $this->requireFields($data, ['user_id', 'reservation_date', 'reservation_time', 'party_size']);
        return $this->dao->insert($this->validatedPayload($data, true));
    }

    public function updateReservation(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $this->validatedPayload($data, false));
        return $this->getById($id);
    }

    private function validatedPayload(array $data, bool $creating): array
    {
        $data = $this->only($data, ['user_id', 'reservation_date', 'reservation_time', 'party_size', 'status', 'notes']);
        if (isset($data['user_id'])) {
            $data['user_id'] = $this->requireInt($data['user_id'], 'user_id');
            if (!$this->userDao->getById($data['user_id'])) {
                throw new AppException('User not found', 400);
            }
        }
        if (isset($data['reservation_date'])) {
            $date = DateTime::createFromFormat('Y-m-d', $data['reservation_date']);
            if (!$date || $date->format('Y-m-d') !== $data['reservation_date']) {
                throw new AppException('reservation_date must be YYYY-MM-DD');
            }
        }
        if (isset($data['reservation_time'])) {
            $time = DateTime::createFromFormat('H:i:s', $data['reservation_time']);
            if (!$time || $time->format('H:i:s') !== $data['reservation_time']) {
                $time = DateTime::createFromFormat('H:i', $data['reservation_time']);
            }
            if (!$time) {
                throw new AppException('reservation_time must be HH:MM or HH:MM:SS');
            }
            $data['reservation_time'] = $time->format('H:i:s');
        }
        if (isset($data['party_size'])) {
            $size = (int) $data['party_size'];
            if ($size < 1 || $size > 20) {
                throw new AppException('party_size must be between 1 and 20');
            }
            $data['party_size'] = $size;
        }
        if (isset($data['status']) && !in_array($data['status'], ['pending', 'confirmed', 'cancelled'], true)) {
            throw new AppException('Invalid reservation status');
        } elseif ($creating) {
            $data['status'] = $data['status'] ?? 'pending';
        }
        return $data;
    }
}
