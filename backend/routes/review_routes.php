<?php

/**
 * @OA\Get(path="/api/reviews", tags={"reviews"}, summary="Get all reviews",
 *     @OA\Response(response=200, description="List of reviews"))
 */
Flight::route('GET /api/reviews', function () {
    api_handle(function () {
        $rows = Flight::reviewService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/reviews/product/{productId}", tags={"reviews"}, summary="Get reviews for a product",
 *     @OA\Parameter(name="productId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Reviews"),
 *     @OA\Response(response=404, description="Product not found"))
 */
Flight::route('GET /api/reviews/product/@productId', function ($productId) {
    api_handle(function () use ($productId) {
        $rows = Flight::reviewService()->getByProductId(path_id($productId));
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/reviews/{id}", tags={"reviews"}, summary="Get review by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Review"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/reviews/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::reviewService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/reviews", tags={"reviews"}, summary="Create review",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"user_id","rating"},
 *         @OA\Property(property="user_id", type="integer", example=2),
 *         @OA\Property(property="product_id", type="integer", example=2),
 *         @OA\Property(property="rating", type="integer", example=5),
 *         @OA\Property(property="comment", type="string", example="Excellent coffee")
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/reviews', function () {
    api_handle(function () {
        $row = Flight::reviewService()->createReview(json_body());
        Flight::json(['success' => true, 'message' => 'Review created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/reviews/{id}", tags={"reviews"}, summary="Update review",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="rating", type="integer", example=4))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/reviews/{id}", tags={"reviews"}, summary="Partial update review",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/reviews/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::reviewService()->updateReview(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Review updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/reviews/{id}", tags={"reviews"}, summary="Delete review",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/reviews/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::reviewService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Review deleted'], 200);
    });
});
