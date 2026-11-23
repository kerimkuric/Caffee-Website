<?php

/**
 * @OA\Get(path="/api/categories", tags={"categories"}, summary="Get all categories",
 *     @OA\Response(response=200, description="List of categories"))
 */
Flight::route('GET /api/categories', function () {
    api_handle(function () {
        $rows = Flight::categoryService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/categories/{id}", tags={"categories"}, summary="Get category by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Category"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/categories/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::categoryService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/categories", tags={"categories"}, summary="Create category",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"name"},
 *         @OA\Property(property="name", type="string", example="Tea"),
 *         @OA\Property(property="description", type="string", example="Loose-leaf tea")
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/categories', function () {
    api_handle(function () {
        $row = Flight::categoryService()->createCategory(json_body());
        Flight::json(['success' => true, 'message' => 'Category created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/categories/{id}", tags={"categories"}, summary="Update category",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="description", type="string"))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/categories/{id}", tags={"categories"}, summary="Partial update category",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/categories/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::categoryService()->updateCategory(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Category updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/categories/{id}", tags={"categories"}, summary="Delete category",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/categories/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::categoryService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Category deleted'], 200);
    });
});
