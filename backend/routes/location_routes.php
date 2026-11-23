<?php

/**
 * @OA\Get(
 *     path="/api/location",
 *     tags={"location"},
 *     summary="Shop location from OpenStreetMap Nominatim (or LocationIQ if an API key is set)",
 *     description="Geocodes the Kod Kere address. Uses a 5 second timeout. Failures are written to the error log. The shop page still shows the street address if the provider is down.",
 *     @OA\Response(response=200, description="Coordinates and map links"),
 *     @OA\Response(response=404, description="Address not found"),
 *     @OA\Response(response=500, description="Timeout or provider error")
 * )
 */
Flight::route('GET /api/location', function () {
    api_handle(function () {
        $data = Flight::locationService()->getShopLocation();
        Flight::json(['success' => true, 'data' => $data], 200);
    });
});
