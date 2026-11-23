<?php

/**
 * @OA\Info(
 *     title="Kod Kere API",
 *     description="REST API for the Kod Kere coffee shop: users, categories, products, reservations, orders, reviews, and shop location.",
 *     version="1.0.0",
 *     @OA\Contact(
 *         email="kerim.kuric@stu.ibu.edu.ba",
 *         name="Kerim Kuric"
 *     )
 * )
 */

/**
 * @OA\Server(
 *     url=BASE_URL,
 *     description="API server"
 * )
 *
 * @OA\Tag(name="users", description="Shop users")
 * @OA\Tag(name="categories", description="Menu categories")
 * @OA\Tag(name="products", description="Drinks and pastry")
 * @OA\Tag(name="reservations", description="Table bookings")
 * @OA\Tag(name="orders", description="Cafe orders")
 * @OA\Tag(name="order_items", description="Products on an order")
 * @OA\Tag(name="reviews", description="Guest reviews")
 * @OA\Tag(name="location", description="Shop map from OpenStreetMap Nominatim")
 */

/**
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authorization",
 *     description="JWT Bearer token (Milestone 4)"
 * )
 */
