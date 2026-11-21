<?php

/**
 * @OA\Get(path="/api/products", tags={"products"}, summary="Get all products",
 *     @OA\Response(response=200, description="List of products"))
 */
Flight::route('GET /api/products', function () {
    api_handle(function () {
        $rows = Flight::productService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/products/category/{categoryId}", tags={"products"}, summary="Get products by category",
 *     @OA\Parameter(name="categoryId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Products in category"),
 *     @OA\Response(response=404, description="Category not found"))
 */
Flight::route('GET /api/products/category/@categoryId', function ($categoryId) {
    api_handle(function () use ($categoryId) {
        $rows = Flight::productService()->getByCategoryId(path_id($categoryId));
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/products/{id}", tags={"products"}, summary="Get product by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Product"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/products/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::productService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/products", tags={"products"}, summary="Create product",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"category_id","name","price"},
 *         @OA\Property(property="category_id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Flat white"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="price", type="number", example=5.5),
 *         @OA\Property(property="image_url", type="string"),
 *         @OA\Property(property="is_available", type="boolean", example=true)
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/products', function () {
    api_handle(function () {
        $row = Flight::productService()->createProduct(json_body());
        Flight::json(['success' => true, 'message' => 'Product created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/products/{id}", tags={"products"}, summary="Update product",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="price", type="number"))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/products/{id}", tags={"products"}, summary="Partial update product",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/products/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::productService()->updateProduct(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Product updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/products/{id}", tags={"products"}, summary="Delete product",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/products/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::productService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Product deleted'], 200);
    });
});
