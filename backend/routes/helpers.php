<?php

function json_body(): array
{
    $raw = Flight::request()->getBody();
    $data = json_decode($raw, true);
    if (!is_array($data) || $data === []) {
        $data = Flight::request()->data->getData();
    }
    if (!is_array($data) || $data === []) {
        throw new AppException('Invalid JSON data or empty request body', 400);
    }
    return $data;
}

function api_handle(callable $fn): void
{
    try {
        $fn();
    } catch (AppException $e) {
        if ($e->getStatus() >= 500) {
            Logger::error($e->getMessage());
        }
        Flight::json([
            'success' => false,
            'message' => $e->getMessage(),
        ], $e->getStatus());
    } catch (Throwable $e) {
        Logger::error($e->getMessage());
        Flight::json([
            'success' => false,
            'message' => 'Server error',
        ], 500);
    }
}

function path_id($id): int
{
    if (!is_numeric($id) || (int) $id < 1) {
        throw new AppException('Invalid id', 400);
    }
    return (int) $id;
}
