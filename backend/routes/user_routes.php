<?php

/**
 * @OA\Get(
 *     path="/api/users",
 *     tags={"users"},
 *     summary="Get all users",
 *     @OA\Response(response=200, description="List of users"),
 *     @OA\Response(response=500, description="Server error")
 * )
 */
Flight::route('GET /api/users', function () {
    api_handle(function () {
        $users = Flight::userService()->getAll();
        Flight::json(['success' => true, 'data' => $users, 'count' => count($users)], 200);
    });
});

/**
 * @OA\Get(
 *     path="/api/users/email/{email}",
 *     tags={"users"},
 *     summary="Get user by email",
 *     @OA\Parameter(name="email", in="path", required=true, @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="User"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
Flight::route('GET /api/users/email/@email', function ($email) {
    api_handle(function () use ($email) {
        Flight::json(['success' => true, 'data' => Flight::userService()->getByEmail($email)], 200);
    });
});

/**
 * @OA\Get(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="User"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
Flight::route('GET /api/users/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::userService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(
 *     path="/api/users",
 *     tags={"users"},
 *     summary="Create user",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"first_name","last_name","email","password"},
 *         @OA\Property(property="first_name", type="string", example="Amina"),
 *         @OA\Property(property="last_name", type="string", example="Hodzic"),
 *         @OA\Property(property="email", type="string", example="amina@kodkere.ba"),
 *         @OA\Property(property="password", type="string", example="Password123!"),
 *         @OA\Property(property="role", type="string", example="customer"),
 *         @OA\Property(property="phone", type="string", example="+387 61 000 000")
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error")
 * )
 */
Flight::route('POST /api/users', function () {
    api_handle(function () {
        $user = Flight::userService()->createUser(json_body());
        Flight::json(['success' => true, 'message' => 'User created', 'data' => $user], 201);
    });
});

/**
 * @OA\Put(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Update user",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         @OA\Property(property="first_name", type="string"),
 *         @OA\Property(property="email", type="string"),
 *         @OA\Property(property="phone", type="string")
 *     )),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=400, description="Validation error"),
 *     @OA\Response(response=404, description="Not found")
 * )
 * @OA\Patch(path="/api/users/{id}", tags={"users"}, summary="Partial update user",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated")
 * )
 */
Flight::route('PUT|PATCH /api/users/@id', function ($id) {
    api_handle(function () use ($id) {
        $user = Flight::userService()->updateUser(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'User updated', 'data' => $user], 200);
    });
});

/**
 * @OA\Delete(
 *     path="/api/users/{id}",
 *     tags={"users"},
 *     summary="Delete user",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found")
 * )
 */
Flight::route('DELETE /api/users/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::userService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'User deleted'], 200);
    });
});
