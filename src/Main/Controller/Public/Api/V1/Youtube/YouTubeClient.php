<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Youtube;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

class YouTubeClient {

    private $apiKey;
    private string $apiBase = 'https://www.googleapis.com/youtube/v3';
    private ?string $accessToken = null;
    private bool $cacheEnabled = true;
    private int $cacheTTL = 300; // 5 minutos
    private string $cacheDir;

    public function __construct($apiKey = null) {
        $config = new McClientConfig();
        $this->apiKey = $apiKey;
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/cache/youtube/";
        // Garante a criação da pasta
        if (!is_dir($this->cacheDir)) {
            @mkdir($this->cacheDir, 0777, true);
        }
    }

    /** Ativa/Desativa Cache */
    public function setCache(bool $enabled, int $ttl = 300) {
        $this->cacheEnabled = $enabled;
        $this->cacheTTL = $ttl;
    }

    public function setAccessToken(string $token) {
        $this->accessToken = $token;
    }

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

    /** --------------------------------- */
    /** MÉTODO HTTP + CACHE AUTOMÁTICO    */

    /** --------------------------------- */
    private function httpGetJson(string $url): array {

        // Tenta pegar do cache
        if ($cached = $this->cacheGet($url)) {
            return $cached;
        }

        // Chamada real à API
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

        if ($err)
            throw new \Exception("cURL error: {$err}");

        $decoded = json_decode($resp, true);

        if ($status < 200 || $status >= 300) {
            $message = $decoded['error']['message'] ?? "HTTP error {$status}";
            throw new \Exception("YouTube API error: {$message}");
        }

        if ($decoded === null)
            throw new \Exception("Invalid JSON from YouTube API");

        // Salva cache
        $this->cacheSet($url, $decoded);

        return $decoded;
    }

    /** ---------------------------------------------------- */
    /** AQUI CONTINUA SUA CLASSE ORIGINAL (SEM ALTERAÇÕES)   */

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

    public function searchVideos(string $query, int $maxResults = 10): array { // <-- fácil alterar quantidade
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

    /**
     * Retorna detalhes de vídeos por ID: snippet, statistics e duration.
     */
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

    /*
     * Listar transcrições disponíveis
     * Isso retorna uma lista como:
      {
      "items": [
      {
      "id": "AABBCC112233",
      "snippet": {
      "language": "en",
      "name": "Automatic",
      "trackKind": "ASR"
      }
      }
      ]
      }
     */

    function getVideoCaptions(string $accessToken, string $videoId): ?array {
        $url = "https://www.googleapis.com/youtube/v3/captions?part=snippet&videoId=" . $videoId;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $accessToken,
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /*
     *  Baixar a transcrição em str
     */

    function downloadCaption(string $accessToken, string $captionId): ?string {
        $url = "https://www.googleapis.com/youtube/v3/captions/{$captionId}?tfmt=srt";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response; // conteúdo .srt
    }

    /*
      Converter o .SRT para texto limpo (PHP)
     *  */

    function srtToText(string $srt): string {
        $lines = explode("\n", $srt);
        $output = "";

        foreach ($lines as $line) {
            // Remove número das legendas
            if (preg_match('/^\d+$/', trim($line)))
                continue;

            // Remove timestamps
            if (strpos($line, "-->") !== false)
                continue;

            // Remove linha vazia
            if (trim($line) === "")
                continue;

            $output .= $line . " ";
        }

        return trim($output);
    }

    /*
      exemplo:
     * 
     * $captionList = getVideoCaptions($accessToken, $videoId);

      if (!empty($captionList['items'])) {
      $captionId = $captionList['items'][0]['id'];

      $srt = downloadCaption($accessToken, $captionId);
      $text = srtToText($srt);

      echo $text;
      } else {
      echo "Nenhuma transcrição disponível.";
      }
     *      */
}
