<?php

namespace Project\Library;

/**
 * Spotify API wrapper
 */
class Spotify
{
    /** @var string $endpoint Spotify endpoint */
    private string $endpoint;

    /** @var string $clientID Spotify Client ID */
    private string $clientID;

    /** @var string $clientSecret Spotify Client Secret */
    private string $clientSecret;

    /** @var string $accessToken Spotify access token */
    private string $accessToken;

    /** @var int $timeout Request timeout */
    private int $timeout = 60;

    /**
     * Search for artist
     * 
     * @param string $name
     * 
     * @return array
     */
    public function findArtist(string $name): array
    {
        try {
            $response = $this->send('/search?q=' . urlencode($name) . '&type=artist', 'GET');
            
            $artists = [];
            if ($response['code'] === 200) {
                foreach($response['body']['artists']['items'] as $res) {
                    if (count($res['images']) === 0) continue;
                    $artists[] = ['spotify_id' => $res['id'], 'name' => $res['name'], 'picture' => $res['images'][0]['url']];
                }
            }

            return $artists;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Search for songs
     * 
     * @param string $title
     * 
     * @return array
     */
    public function findTrack(string $title): array
    {
        try {
            $response = $this->send('/search?q=' . urlencode($title) . '&type=track', 'GET');

            $tracks = [];
            if ($response['code'] === 200) {
                foreach ($response['body']['tracks']['items'] as $res) {
                    $tracks[] = [
                        'artist' => $res['artists'][0]['name'],
                        'name' => $res['name'],
                        'spotify_id' => $res['id'],
                        'picture' => $res['album']['images'][0]['url'],
                        'duration' => $res['duration_ms'],
                        'score' => 0,
                        'color' => $this->generateColor()
                    ];
                }
            }

            return $tracks;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Send request to Spotify API
     * 
     * @param string $uri
     * @param string $method
     * @param ?array $data
     * 
     * @return array
     */
    private function send(string $uri, string $method, ?array $data = null): array
    {
        $this->setEnvVariables();

        try {
            $context = stream_context_create($this->buildContext(strtoupper($method), $data));
            $response = file_get_contents($this->endpoint . $uri, false, $context);

            $code = $this->parseHeaders($http_response_header)['response_code'];
            if ($code === 401)
                $this->setAccessToken();

            return [
                'code' => $code,
                'body' => json_decode($response, true)
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Build HTTP request
     * 
     * @param string $method
     * @param ?array $data
     * 
     * @return array
     */
    private function buildContext(string $method, ?array $data = null): array
    {
        if (!is_null($data)) {
            return [
                'http' => [
                    'header' => 'Content-type: application/json\r\n' .
                                'Authorization: Bearer  ' . $this->accessToken,
                    'method' => strtoupper($method),
                    'content' => json_encode($data),
                    'ignore_errors' => true
                ]
            ];
        } else {
            return [
                'http' => [
                    'header' => 'Authorization: Bearer  ' . $this->accessToken,
                    'method' => strtoupper($method),
                    'ignore_errors' => true
                ]
            ];
        }
    }

    /**
     * Parse response headers
     * 
     * @param array $headers
     * 
     * @return array
     */
    private function parseHeaders(array $headers): array
    {
        $head = array();
        foreach ($headers as $key => $value) {
            $t = explode(':', $value, 2);
            if (isset($t[1]))
                $head[trim($t[0])] = trim($t[1]);
            else {
                $head[] = $value;
                if(preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $value, $out))
                    $head['response_code'] = intval($out[1]);
            }
        }
        return $head;
    }

    /**
     * Set endpoint URL and API key
     */
    private function setEnvVariables(): void
    {
        $this->endpoint = $_ENV['SPOTIFY_ENDPOINT'];
        $this->clientID = $_ENV['SPOTIFY_CLIENT_ID'];
        $this->clientSecret = $_ENV['SPOTIFY_CLIENT_SECRET'];

        if (strlen($_ENV['SPOTIFY_ACCESS_TOKEN']) === 0) {
            $this->setAccessToken();
        }

        $this->accessToken = $_ENV['SPOTIFY_ACCESS_TOKEN'];
    }

    /**
     * Get Spotify API access token
     */
    private function setAccessToken(): void
    {
        $url = 'https://accounts.spotify.com/api/token';
        $query = http_build_query(
            [
                "grant_type" =>  "client_credentials",
                "client_id" => $this->clientID,
                "client_secret" => $this->clientSecret
            ]
        );
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded",
                'method' => 'POST',
                'content' => $query
            ]
        ];

        $context = stream_context_create($options);

        $response = json_decode(file_get_contents($url, false, $context), true);

        $envPath = __DIR__ . '/../.env';
        if (file_exists($envPath)) {
            file_put_contents(
                $envPath,
                str_replace(
                    "SPOTIFY_ACCESS_TOKEN='" . $_ENV['SPOTIFY_ACCESS_TOKEN'] . "'",
                    "SPOTIFY_ACCESS_TOKEN='" . $response["access_token"] . "'",
                    file_get_contents($envPath)
                )
            );
        }

        $this->accessToken = $_ENV['SPOTIFY_ACCESS_TOKEN'];
    }

    /**
     * Generate a hex color
     * 
     * @return string
     */
    private function generateColor(): string
    {
        return '#' . $this->generateRandomHex() . $this->generateRandomHex() . $this->generateRandomHex();
    }

    /**
     * Generate random hex
     * 
     * @return string
     */
    private function generateRandomHex(): string
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
}