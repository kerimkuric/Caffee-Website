<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ReviewService extends BaseService
{
    private UserDao $userDao;
    private ProductDao $productDao;

    public function __construct()
    {
        parent::__construct(new ReviewDao());
        $this->userDao = new UserDao();
        $this->productDao = new ProductDao();
    }

    public function getByProductId(int $productId): array
    {
        if (!$this->productDao->getById($productId)) {
            throw new AppException('Product not found', 404);
        }
        return $this->dao->getByProductId($productId);
    }

    public function createReview(array $data): array
    {
        $this->requireFields($data, ['user_id', 'rating']);
        return $this->dao->insert($this->validatedPayload($data, true));
    }

    public function updateReview(int $id, array $data): array
    {
        $this->getById($id);
        $this->dao->update($id, $this->validatedPayload($data, false));
        return $this->getById($id);
    }

    private function validatedPayload(array $data, bool $creating): array
    {
        $data = $this->only($data, ['user_id', 'product_id', 'rating', 'comment']);
        if (isset($data['user_id'])) {
            $data['user_id'] = $this->requireInt($data['user_id'], 'user_id');
            if (!$this->userDao->getById($data['user_id'])) {
                throw new AppException('User not found', 400);
            }
        }
        if (array_key_exists('product_id', $data) && $data['product_id'] !== null && $data['product_id'] !== '') {
            $data['product_id'] = $this->requireInt($data['product_id'], 'product_id');
            if (!$this->productDao->getById($data['product_id'])) {
                throw new AppException('Product not found', 400);
            }
        }
        if (isset($data['rating'])) {
            $rating = (int) $data['rating'];
            if ($rating < 1 || $rating > 5) {
                throw new AppException('rating must be between 1 and 5');
            }
            $data['rating'] = $rating;
        }
        if (isset($data['comment']) && strlen($data['comment']) > 500) {
            throw new AppException('comment must be 500 characters or fewer');
        }
        return $data;
    }
}
