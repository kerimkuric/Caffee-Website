<?php

/**
 * @OA\Get(path="/api/orders", tags={"orders"}, summary="Get all orders",
 *     @OA\Response(response=200, description="List of orders"))
 */
Flight::route('GET /api/orders', function () {
    api_handle(function () {
        $rows = Flight::orderService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/orders/user/{userId}", tags={"orders"}, summary="Get orders for a user",
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Orders"),
 *     @OA\Response(response=404, description="User not found"))
 */
Flight::route('GET /api/orders/user/@userId', function ($userId) {
    api_handle(function () use ($userId) {
        $rows = Flight::orderService()->getByUserId(path_id($userId));
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/orders/{id}", tags={"orders"}, summary="Get order by ID (with items)",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/orders/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::orderService()->getWithItems(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/orders", tags={"orders"}, summary="Create order",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"user_id"},
 *         @OA\Property(property="user_id", type="integer", example=2),
 *         @OA\Property(property="status", type="string", example="pending"),
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="product_id", type="integer", example=2),
 *                 @OA\Property(property="quantity", type="integer", example=1)
 *             )
 *         )
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/orders', function () {
    api_handle(function () {
        $row = Flight::orderService()->createOrder(json_body());
        Flight::json(['success' => true, 'message' => 'Order created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/orders/{id}", tags={"orders"}, summary="Update order status or total",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="status", type="string", example="ready"))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/orders/{id}", tags={"orders"}, summary="Partial update order",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/orders/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::orderService()->updateOrder(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Order updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/orders/{id}", tags={"orders"}, summary="Delete order",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/orders/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::orderService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Order deleted'], 200);
    });
});
