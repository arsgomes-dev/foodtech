<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Youtube;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

class YouTubeApi {

    private string $apiKey;
    private string $apiBase = 'https://www.googleapis.com/youtube/v3';
    private bool $cacheEnabled = true;
    private int $cacheTTL = 86400; // 5 minutos
    private string $cacheDir;

    public function __construct(string $apiKey) {
        $config = new McClientConfig();
        $this->apiKey = $apiKey;
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/cache/youtube/";
        // Garante a criação da pasta
        if (!is_dir($this->cacheDir)) {
            @mkdir($this->cacheDir, 0777, true);
        }
    }

    /**
     * Busca vídeos relacionados a uma query e retorna array com snippet + statistics.
     *
     * @param string $query
     * @param int $maxResults
     * @return array
     * @throws \Exception
     */
    /** ----------------------------- */
    /** SISTEMA DE CACHE EM ARQUIVO   */

    /** ----------------------------- */
    private function cacheGet(string $key): ?array {
        if (!$this->cacheEnabled)
            return null;

        $file = $this->cacheDir . md5($key) . ".json";

        if (!file_exists($file))
            return null;

        // Verifica expiração
        if (filemtime($file) + $this->cacheTTL < time()) {
            unlink($file);
            return null;
        }

        $json = file_get_contents($file);
        return json_decode($json, true);
    }

    private function cacheSet(string $key, array $data): void {
        if (!$this->cacheEnabled)
            return;

        $file = $this->cacheDir . md5($key) . ".json";
        file_put_contents($file, json_encode($data));
    }

    /** ---------------------------------------------------- */
    public function isoToSeconds($duration) {
        try {
            $interval = new \DateInterval($duration);
            return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function isType($isLive, $duration, $thumb) {
        try {
            $isShort = ($duration <= 60);

            $thumbData = $thumb['maxres'] ?? $thumb['standard'] ?? $thumb['high'] ?? $thumb['medium'] ?? $thumb['thumbnails']['default'];
            $thumbWidth = $thumbData['width'];
            $thumbHeight = $thumbData['height'];

            $isVertical = ($thumbHeight > $thumbWidth);

            if ($isLive)
                return "LIVE AO VIVO";
            elseif ($isShort || $isVertical)
                return "SHORT";
            else
                return "HORIZONTAL";
        } catch (Exception $e) {
            return 0;
        }
    }

    public function timeAgo($dateString) {
        if (!$dateString) {
            return "Data não informada";
        }

        $date = new \DateTime($dateString);
        $now = new \DateTime();

        $diff = $now->diff($date);

        if ($diff->y > 0)
            return $diff->y . " ano" . ($diff->y > 1 ? "s" : "") . " atrás";
        if ($diff->m > 0)
            return $diff->m . " mês" . ($diff->m > 1 ? "es" : "") . " atrás";
        if ($diff->d > 0)
            return $diff->d . " dia" . ($diff->d > 1 ? "s" : "") . " atrás";
        if ($diff->h > 0)
            return $diff->h . " hora" . ($diff->h > 1 ? "s" : "") . " atrás";
        if ($diff->i > 0)
            return $diff->i . " minuto" . ($diff->i > 1 ? "s" : "") . " atrás";

        return "Agora mesmo";
    }

    public function searchVideos(string $query, int $maxResults = 10): array {
        $query = trim($query);
        if ($query === '')
            return [];

        $params = http_build_query([
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'maxResults' => $maxResults,
            'key' => $this->apiKey,
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $searchResponse = $this->httpGetJson($url);

        if (empty($searchResponse['items']))
            return [];

        $videoIds = [];
        foreach ($searchResponse['items'] as $item) {
            if (!empty($item['id']['videoId']))
                $videoIds[] = $item['id']['videoId'];
        }

        if (empty($videoIds))
            return [];

        return $this->getVideosDetails($videoIds);
    }

    private function httpGetJson(string $url): array {
        // Tenta pegar do cache
        if ($cached = $this->cacheGet($url)) {
            return $cached;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        // user agent para evitar bloqueios simples
        curl_setopt($ch, CURLOPT_USERAGENT, 'YouTubeClient/1.0');

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) {
            throw new \Exception("cURL error: {$err}");
        }

        if ($status < 200 || $status >= 300) {
            // tentar decodificar retorno para mensagem melhor
            $decoded = json_decode($resp, true);
            $message = $decoded['error']['message'] ?? "HTTP status {$status}";
            throw new \Exception("YouTube API error: {$message}");
        }

        $decoded = json_decode($resp, true);
        if ($decoded === null) {
            throw new \Exception("Failed decoding JSON from YouTube API.");
        }
        // Salva cache
        $this->cacheSet($url, $decoded);

        return $decoded;
    }

    public function getVideosDetails(array $videoIds): array {
        if (empty($videoIds))
            return [];

        $params = http_build_query([
            'part' => 'snippet,statistics,contentDetails',
            'id' => implode(',', $videoIds),
            'key' => $this->apiKey
        ]);

        $url = "{$this->apiBase}/videos?{$params}";
        $response = $this->httpGetJson($url);

        $result = [];
        if (!empty($response['items'])) {
            foreach ($response['items'] as $item) {
                $result[] = [
                    'id' => $item['id'] ?? null,
                    'title' => $item['snippet']['title'] ?? null,
                    'description' => $item['snippet']['description'] ?? null,
                    'channelTitle' => $item['snippet']['channelTitle'] ?? null,
                    'channelId' => $item['snippet']['channelId'] ?? null,
                    'publishedAt' => $item['snippet']['publishedAt'] ?? null,
                    'time' => $this->timeAgo($item['snippet']['publishedAt']),
                    'thumbnails' => $item['snippet']['thumbnails'] ?? [],
                    //'thumbnails' => $item['snippet']['thumbnails']['maxres'] ?? $item['snippet']['thumbnails']['standard'] ?? $item['snippet']['thumbnails']['high'] ?? $item['snippet']['thumbnails']['medium'] ?? $item['snippet']['thumbnails']['default'],
                    'viewCount' => isset($item['statistics']['viewCount']) ? (int) $item['statistics']['viewCount'] : 0,
                    'likeCount' => isset($item['statistics']['likeCount']) ? (int) $item['statistics']['likeCount'] : 0,
                    'commentCount' => isset($item['statistics']['commentCount']) ? (int) $item['statistics']['commentCount'] : 0,
                    'duration' => $this->isoToSeconds($item['contentDetails']['duration'] ?? 0),
                    'type' => $this->isType(isset($item['snippet']['liveBroadcastContent']) && $item['snippet']['liveBroadcastContent'] === 'live', $this->isoToSeconds($item['contentDetails']['duration'] ?? 0), $item['snippet']['thumbnails'] ?? [])
                        ,
                ];
            }
        }

        usort($result, fn($a, $b) => ($b['viewCount'] ?? 0) <=> ($a['viewCount'] ?? 0));

        return $result;
    }

    /**
     * Últimos vídeos do canal
     */
    public function getChannelVideos(string $channelId, int $maxResults = 5): array {
        $params = http_build_query([
            'part' => 'snippet',
            'channelId' => $channelId,
            'maxResults' => $maxResults,
            'order' => 'date',
            'type' => 'video',
            'key' => $this->apiKey,
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $response = $this->httpGetJson($url);

        if (empty($response['items']))
            return [];

        $videoIds = [];
        foreach ($response['items'] as $item) {
            if (!empty($item['id']['videoId']))
                $videoIds[] = $item['id']['videoId'];
        }

        return $this->getVideosDetails($videoIds);
    }

    /**
     * Média de views dos últimos vídeos do canal
     */
    public function getChannelAverageViews(string $channelId, int $limit = 5): int {
        $videos = $this->getChannelVideos($channelId, $limit);

        if (empty($videos))
            return 0;

        $total = 0;
        $count = 0;

        foreach ($videos as $v) {
            if (!empty($v['viewCount'])) {
                $total += $v['viewCount'];
                $count++;
            }
        }

        return $count > 0 ? intval($total / $count) : 0;
    }

    public function getChannelDetailsManual(string $input): ?array {
        // 1) Extrai o ID real do canal, independente de ser link, @handle ou ID UC...
        $channelId = $this->extractChannelId($input);

        if (!$channelId) {
            return null;
        }

        // 2) Busca os detalhes do canal pela YouTube API
        $params = http_build_query([
            'part' => 'snippet,statistics',
            'id' => $channelId,
            'key' => $this->apiKey
        ]);

        $url = "{$this->apiBase}/channels?{$params}";
        $response = $this->httpGetJson($url);

        if (empty($response['items'][0])) {
            return null;
        }

        $item = $response['items'][0];

        return [
            'channelId' => $item['id'] ?? '',
            'title' => $item['snippet']['title'] ?? '',
            'description' => $item['snippet']['description'] ?? '',
            'thumbnail' => $item['snippet']['thumbnails']['high']['url'] ?? '',
            'subscribers' => isset($item['statistics']['subscriberCount']) ? (int) $item['statistics']['subscriberCount'] : 0,
            'views' => isset($item['statistics']['viewCount']) ? (int) $item['statistics']['viewCount'] : 0,
            'videos' => isset($item['statistics']['videoCount']) ? (int) $item['statistics']['videoCount'] : 0,
            'published_at' => $item['snippet']['publishedAt'] ?? null,
        ];
    }

    public function extractChannelId(string $input): ?string {
        $input = trim($input);

        // Caso já seja um ID válido UC...
        if (preg_match('/^UC[a-zA-Z0-9_-]{20,}$/', $input)) {
            return $input;
        }

        // Formatos possíveis:
        // 1) /channel/UCxxxx
        if (preg_match('#youtube\.com/channel/([^/?]+)#i', $input, $m)) {
            return $m[1];
        }

        // 2) /user/NomeDoUsuario
        if (preg_match('#youtube\.com/user/([^/?]+)#i', $input, $m)) {
            return $this->resolveUserToChannelId($m[1]);
        }

        // 3) /@handle
        if (preg_match('#youtube\.com/@([^/?]+)#i', $input, $m)) {
            return $this->resolveHandleToChannelId($m[1]);
        }

        return null; // inválido
    }

    public function resolveHandleToChannelId(string $handle): ?string {
        $params = http_build_query([
            'part' => 'snippet',
            'q' => '@' . $handle,
            'type' => 'channel',
            'key' => $this->apiKey
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $response = $this->httpGetJson($url);

        if (!empty($response['items'][0]['snippet']['channelId'])) {
            return $response['items'][0]['snippet']['channelId'];
        }

        return null;
    }

    public function resolveUserToChannelId(string $user): ?string {
        $params = http_build_query([
            'part' => 'snippet',
            'q' => $user,
            'type' => 'channel',
            'key' => $this->apiKey
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $response = $this->httpGetJson($url);

        if (!empty($response['items'][0]['snippet']['channelId'])) {
            return $response['items'][0]['snippet']['channelId'];
        }

        return null;
    }

    public function getVideosStats(array $videoIds): array {
        $totalViews = 0;
        $totalVideos = 0;
        $engagement = 0;
        $recentCount = 0;
        $now = new \DateTime();
        if (empty($videoIds))
            return [];

        $params = http_build_query([
            'part' => 'statistics,snippet',
            'id' => implode(',', $videoIds),
            'key' => $this->apiKey
        ]);

        $url = "{$this->apiBase}/videos?{$params}";
        $response = $this->httpGetJson($url);

        if (!empty($response['items'])) {
            foreach ($response['items'] as $item) {
                $views = (int) isset($item['statistics']['viewCount']) ? (int) $item['statistics']['viewCount'] : 0;
                $likes = (int) isset($item['statistics']['likeCount']) ? (int) $item['statistics']['likeCount'] : 0;
                $comments = (int) isset($item['statistics']['commentCount']) ? (int) $item['statistics']['commentCount'] : 0;
                $pub = new \DateTime($item['snippet']['publishedAt'] ?? $now->format(DATE_ATOM));
                $diffDays = (int) $now->diff($pub)->days;
                if ($diffDays <= 90)
                    $recentCount++; // vídeo recente (3 meses)
                $totalViews += $views;
                $engagement += ($likes + $comments);
                $totalVideos++;
            }
        }

        $data = ['totalViews' => $totalViews, 'engagement' => $engagement, 'totalVideos' => $totalVideos, 'recentCount' => $recentCount];

        return $data;
    }

    /**
     * Últimos vídeos do canal
     */
    public function getVideosID($query, $maxResults = 5): array {
        // Monta os parâmetros da API
        $params = http_build_query([
            'part' => 'snippet',
            'q' => $query,
            'maxResults' => $maxResults,
            'type' => 'video',
            'order' => 'relevance',
            'safeSearch' => 'none',
            'key' => $this->apiKey,
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $response = $this->httpGetJson($url);

        if (empty($response['items']) || !is_array($response['items'])) {
            return [];
        }

        // Extrai somente os IDs válidos de vídeo
        $videoIds = [];
        foreach ($response['items'] as $item) {
            if (isset($item['id']['videoId']) && !empty($item['id']['videoId'])) {
                $videoIds[] = $item['id']['videoId'];
            }
        }

        return $videoIds;
    }
}
