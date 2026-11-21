<?php

/**
 * @OA\Get(path="/api/reservations", tags={"reservations"}, summary="Get all reservations",
 *     @OA\Response(response=200, description="List of reservations"))
 */
Flight::route('GET /api/reservations', function () {
    api_handle(function () {
        $rows = Flight::reservationService()->getAll();
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/reservations/user/{userId}", tags={"reservations"}, summary="Get reservations for a user",
 *     @OA\Parameter(name="userId", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Reservations"),
 *     @OA\Response(response=404, description="User not found"))
 */
Flight::route('GET /api/reservations/user/@userId', function ($userId) {
    api_handle(function () use ($userId) {
        $rows = Flight::reservationService()->getByUserId(path_id($userId));
        Flight::json(['success' => true, 'data' => $rows, 'count' => count($rows)], 200);
    });
});

/**
 * @OA\Get(path="/api/reservations/{id}", tags={"reservations"}, summary="Get reservation by ID",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Reservation"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('GET /api/reservations/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::json(['success' => true, 'data' => Flight::reservationService()->getById(path_id($id))], 200);
    });
});

/**
 * @OA\Post(path="/api/reservations", tags={"reservations"}, summary="Create reservation",
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         required={"user_id","reservation_date","reservation_time","party_size"},
 *         @OA\Property(property="user_id", type="integer", example=2),
 *         @OA\Property(property="reservation_date", type="string", example="2026-12-01"),
 *         @OA\Property(property="reservation_time", type="string", example="18:00:00"),
 *         @OA\Property(property="party_size", type="integer", example=2),
 *         @OA\Property(property="notes", type="string", example="Window table")
 *     )),
 *     @OA\Response(response=201, description="Created"),
 *     @OA\Response(response=400, description="Validation error"))
 */
Flight::route('POST /api/reservations', function () {
    api_handle(function () {
        $row = Flight::reservationService()->createReservation(json_body());
        Flight::json(['success' => true, 'message' => 'Reservation created', 'data' => $row], 201);
    });
});

/**
 * @OA\Put(path="/api/reservations/{id}", tags={"reservations"}, summary="Update reservation",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\RequestBody(required=true, @OA\JsonContent(@OA\Property(property="status", type="string", example="confirmed"))),
 *     @OA\Response(response=200, description="Updated"),
 *     @OA\Response(response=404, description="Not found"))
 * @OA\Patch(path="/api/reservations/{id}", tags={"reservations"}, summary="Partial update reservation",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Updated"))
 */
Flight::route('PUT|PATCH /api/reservations/@id', function ($id) {
    api_handle(function () use ($id) {
        $row = Flight::reservationService()->updateReservation(path_id($id), json_body());
        Flight::json(['success' => true, 'message' => 'Reservation updated', 'data' => $row], 200);
    });
});

/**
 * @OA\Delete(path="/api/reservations/{id}", tags={"reservations"}, summary="Delete reservation",
 *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Deleted"),
 *     @OA\Response(response=404, description="Not found"))
 */
Flight::route('DELETE /api/reservations/@id', function ($id) {
    api_handle(function () use ($id) {
        Flight::reservationService()->delete(path_id($id));
        Flight::json(['success' => true, 'message' => 'Reservation deleted'], 200);
    });
});
