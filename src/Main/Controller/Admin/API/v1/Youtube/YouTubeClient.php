<?php

namespace Microfw\Src\Main\Controller\Admin\Api\V1\Youtube;

class YouTubeClient {

    private $apiKey;
    private string $apiBase = 'https://www.googleapis.com/youtube/v3';
    private ?string $accessToken = null;

    public function __construct($apiKey = null) {
        $this->apiKey = $apiKey;
    }

    public function setAccessToken(string $token) {
        $this->accessToken = $token;
    }

    /**
     * Busca vídeos relacionados a uma query e retorna snippet + statistics.
     */
    public function searchVideos(string $query, int $maxResults = 10): array { // <-- fácil alterar quantidade
        $query = trim($query);
        if ($query === '') return [];

        $params = http_build_query([
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'maxResults' => $maxResults,
            'key' => $this->apiKey,
        ]);

        $url = "{$this->apiBase}/search?{$params}";
        $searchResponse = $this->httpGetJson($url);

        if (empty($searchResponse['items'])) return [];

        $videoIds = [];
        foreach ($searchResponse['items'] as $item) {
            if (!empty($item['id']['videoId'])) $videoIds[] = $item['id']['videoId'];
        }

        if (empty($videoIds)) return [];

        return $this->getVideosDetails($videoIds);
    }

    /**
     * Retorna detalhes de vídeos por ID: snippet, statistics e duration.
     */
    public function getVideosDetails(array $videoIds): array {
        if (empty($videoIds)) return [];

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
                    'thumbnails' => $item['snippet']['thumbnails'] ?? [],
                    'viewCount' => isset($item['statistics']['viewCount']) ? (int)$item['statistics']['viewCount'] : 0,
                    'likeCount' => isset($item['statistics']['likeCount']) ? (int)$item['statistics']['likeCount'] : 0,
                    'commentCount' => isset($item['statistics']['commentCount']) ? (int)$item['statistics']['commentCount'] : 0,
                    'duration' => $item['contentDetails']['duration'] ?? null,
                ];
            }
        }

        usort($result, fn($a, $b) => ($b['viewCount'] ?? 0) <=> ($a['viewCount'] ?? 0));

        return $result;
    }

    /**
     * GET básico com retorno JSON
     */
    private function httpGetJson(string $url): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'YouTubeClient/1.0');

        if (!empty($this->accessToken)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$this->accessToken}"
            ]);
        }

        $resp = curl_exec($ch);
        $err = curl_error($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($err) throw new \Exception("cURL error: {$err}");
        $decoded = json_decode($resp, true);

        if ($status < 200 || $status >= 300) {
            $message = $decoded['error']['message'] ?? "HTTP error {$status}";
            throw new \Exception("YouTube API error: {$message}");
        }

        if ($decoded === null) throw new \Exception("Invalid JSON response from YouTube API");

        return $decoded;
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

        if (empty($response['items'])) return [];

        $videoIds = [];
        foreach ($response['items'] as $item) {
            if (!empty($item['id']['videoId'])) $videoIds[] = $item['id']['videoId'];
        }

        return $this->getVideosDetails($videoIds);
    }

    /**
     * Média de views dos últimos vídeos do canal
     */
    public function getChannelAverageViews(string $channelId, int $limit = 5): int {
        $videos = $this->getChannelVideos($channelId, $limit);

        if (empty($videos)) return 0;

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
}
