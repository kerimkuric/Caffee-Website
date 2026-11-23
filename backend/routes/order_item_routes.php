<?php

/**
 * @OA\Get(path="/api/order-items", tags={"order_items"}, summary="Get all order items",
 *     @OA\Response(response=200, description="List of order items"))
 */
Flight::route('GET /api/order-items', function () {
    api_handle(function () {
        $rows = Flight::orderItemService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/order-items/order/{orderId}", tags={"order_items"}, summary="Get items for an order",
 *     @OA\Parameter(name="orderId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Items"),
 *     @OA\Response(response=404, description="Order not found"))
 */
Flight::route('GET /api/order-items/order/@orderId', function ($orderId) {
    api_handle(function () use ($orderId) {
        $rows = Flight::orderItemService()->getByOrderId(path_id($orderId));
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/order-items/{id}", tags={"order_items"}, summary="Get order item by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Order item"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/order-items/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::orderItemService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/order-items", tags={"order_items"}, summary="Create order item",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"order_id","product_id","quantity"},
 *         @OA\Property(property="order_id", type="integer", example=1),
 *         @OA\Property(property="product_id", type="integer", example=3),
 *         @OA\Property(property="quantity", type="integer", example=2)
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/order-items', function () {
    api_handle(function () {
        $row = Flight::orderItemService()->createItem(json_body());
        Flight::json(['success' => true, 'message' => 'Order item created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/order-items/{id}", tags={"order_items"}, summary="Update order item",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="quantity", type="integer", example=3))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/order-items/{id}", tags={"order_items"}, summary="Partial update order item",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/order-items/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::orderItemService()->updateItem(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Order item updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/order-items/{id}", tags={"order_items"}, summary="Delete order item",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/order-items/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::orderItemService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Order item deleted'], 200);
    });
});
