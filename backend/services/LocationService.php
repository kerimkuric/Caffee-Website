<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/AppException.php';
require_once __DIR__ . '/Logger.php';

class LocationService
{
    private const TIMEOUT_SECONDS = 5;

    public function getShopLocation(): array
    {
        $address = Config::SHOP_ADDRESS();
        $key = Config::LOCATIONIQ_API_KEY();
        $useLocationIq = $key !== '' && $key !== 'YOUR_LOCATIONIQ_KEY';

        if ($useLocationIq) {
            $url = 'https://us1.locationiq.com/v1/search?key=' . urlencode($key)
                . '&q=' . urlencode($address)
                . '&format=json&limit=1';
            $provider = 'LocationIQ';
            $headers = ['Accept: application/json'];
        } else {
            $url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q=' . urlencode($address);
            $provider = 'Nominatim';
            $headers = [
                'Accept: application/json',
                'User-Agent: ' . Config::NOMINATIM_USER_AGENT(),
            ];
        }

        Logger::info("External API $provider GET shop location");

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => self::TIMEOUT_SECONDS,
            CURLOPT_CONNECTTIMEOUT => self::TIMEOUT_SECONDS,
            CURLOPT_HTTPHEADER     => $headers,
        ]);

        $body = curl_exec($ch);
        $errno = curl_errno($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno === CURLE_OPERATION_TIMEDOUT) {
            Logger::error("External API $provider timeout after " . self::TIMEOUT_SECONDS . 's');
            throw new AppException('Location service timed out. The shop is still at ' . $address . '.', 500);
        }
        if ($errno !== CURLE_OK || $body === false || $httpCode < 200 || $httpCode >= 300) {
            Logger::error("External API $provider request failed (cURL $errno, HTTP $httpCode)");
            throw new AppException('Location service is unavailable. Please use the address: ' . $address . '.', 500);
        }

        $json = json_decode($body, true);
        if (!is_array($json)) {
            Logger::error("External API $provider returned invalid JSON");
            throw new AppException('Location service returned an invalid response. Please use the address: ' . $address . '.', 500);
        }
        if ($json === []) {
            Logger::error("External API $provider returned no results");
            throw new AppException('Could not find the shop on the map. Address: ' . $address . '.', 404);
        }

        $hit = $json[0] ?? null;
        if (!is_array($hit) || !isset($hit['lat'], $hit['lon'])
            || !is_numeric($hit['lat']) || !is_numeric($hit['lon'])) {
            Logger::error("External API $provider returned an invalid location result");
            throw new AppException('Location service returned an invalid result. Please use the address: ' . $address . '.', 500);
        }
        Logger::info("External API $provider 200 lat=" . ($hit['lat'] ?? ''));

        $lat = $hit['lat'] ?? null;
        $lon = $hit['lon'] ?? null;

        return [
            'provider' => $provider,
            'query' => $address,
            'display_name' => $hit['display_name'] ?? $address,
            'lat' => $lat,
            'lon' => $lon,
            'map_url' => ($lat && $lon)
                ? 'https://www.openstreetmap.org/?mlat=' . $lat . '&mlon=' . $lon . '#map=18/' . $lat . '/' . $lon
                : null,
            'embed_url' => ($lat && $lon)
                ? 'https://www.openstreetmap.org/export/embed.html?bbox='
                    . ((float) $lon - 0.005) . '%2C' . ((float) $lat - 0.003) . '%2C'
                    . ((float) $lon + 0.005) . '%2C' . ((float) $lat + 0.003)
                    . '&layer=mapnik&marker=' . $lat . '%2C' . $lon
                : null,
        ];
    }
}
