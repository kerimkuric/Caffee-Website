<?php
require_once __DIR__ . '/BaseDao.php';

class ReservationDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('reservations');
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM reservations WHERE user_id = :user_id ORDER BY reservation_date, reservation_time'
        );
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
