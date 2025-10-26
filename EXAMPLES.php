<?php

/**
 * LaraCurl - Kullanım Örnekleri
 * 
 * Bu dosya, LaraCurl paketinin çeşitli kullanım örneklerini içerir.
 */

use Curl;

// ============================================
// BASIT ÖRNEKLER
// ============================================

// 1. Basit GET isteği
$response = Curl::get('https://jsonplaceholder.typicode.com/posts/1');
echo $response->response();

// 2. JSON response al
$post = Curl::get('https://jsonplaceholder.typicode.com/posts/1')->json();
echo $post['title'];

// 3. Query parametreleri ile GET
$users = Curl::get('https://api.example.com/users', [
    'page' => 1,
    'per_page' => 10,
    'sort' => 'name'
])->json();

// 4. POST ile JSON gönderme
$response = Curl::post('https://jsonplaceholder.typicode.com/posts')
    ->json([
        'title' => 'Yeni Post',
        'body' => 'Post içeriği',
        'userId' => 1
    ]);

if ($response->successful()) {
    $newPost = $response->json();
    echo "Yeni post oluşturuldu: ID " . $newPost['id'];
}

// ============================================
// AUTHENTICATION ÖRNEKLERİ
// ============================================

// 5. Bearer Token ile istek
$response = Curl::get('https://api.github.com/user')
    ->bearerToken('ghp_your_token_here');

if ($response->successful()) {
    $user = $response->json();
    echo "Merhaba, " . $user['name'];
}

// 6. Basic Authentication
$response = Curl::get('https://api.example.com/protected')
    ->basicAuth('username', 'password');

// 7. Digest Authentication
$response = Curl::get('https://api.example.com/protected')
    ->digestAuth('username', 'password');

// ============================================
// HEADER YÖNETİMİ
// ============================================

// 8. Custom header ekleme
$response = Curl::get('https://api.example.com/data')
    ->header('X-API-Key', 'your-api-key')
    ->header('X-Custom-Header', 'custom-value');

// 9. Çoklu header ekleme
$response = Curl::get('https://api.example.com/data')
    ->headers([
        'X-API-Key' => 'your-api-key',
        'X-Request-ID' => uniqid(),
        'X-Client-Version' => '1.0.0'
    ]);

// ============================================
// DOSYA İŞLEMLERİ
// ============================================

// 10. Dosya yükleme
$response = Curl::post('https://api.example.com/upload')
    ->attach('file', storage_path('temp/document.pdf'), 'application/pdf')
    ->multipart([
        'title' => 'Önemli Döküman',
        'category' => 'reports'
    ]);

// 11. Çoklu dosya yükleme
$response = Curl::post('https://api.example.com/upload')
    ->attach('image1', '/path/to/image1.jpg', 'image/jpeg')
    ->attach('image2', '/path/to/image2.jpg', 'image/jpeg')
    ->attach('document', '/path/to/file.pdf', 'application/pdf');

// 12. Dosya indirme
$response = Curl::get('https://example.com/files/report.pdf')
    ->timeout(300);

if ($response->successful()) {
    $response->save(storage_path('downloads/report.pdf'));
    echo "Dosya indirildi!";
}

// ============================================
// FORM DATA
// ============================================

// 13. Form data gönderme
$response = Curl::post('https://example.com/login')
    ->form([
        'username' => 'zeyd',
        'password' => 'secret',
        'remember' => true
    ]);

// 14. URL encoded form
$response = Curl::post('https://api.example.com/contact')
    ->header('Content-Type', 'application/x-www-form-urlencoded')
    ->form([
        'name' => 'Zeyd Kazancı',
        'email' => 'zeyd@example.com',
        'message' => 'Merhaba!'
    ]);

// ============================================
// TIMEOUT VE AYARLAR
// ============================================

// 15. Timeout ayarlama
$response = Curl::get('https://slow-api.example.com/data')
    ->timeout(60)
    ->connectTimeout(10);

// 16. SSL doğrulamayı kapatma (development için)
$response = Curl::get('https://self-signed.local')
    ->sslVerify(false);

// 17. User Agent ve Referer
$response = Curl::get('https://example.com')
    ->userAgent('MyBot/1.0 (+https://example.com/bot)')
    ->referer('https://google.com');

// 18. Yönlendirmeleri kontrol etme
$response = Curl::get('https://example.com/redirect')
    ->followRedirects(true, 10); // Maksimum 10 yönlendirme

// ============================================
// PROXY KULLANIMI
// ============================================

// 19. Proxy ile istek
$response = Curl::get('https://api.example.com/data')
    ->proxy('proxy.example.com', 8080);

// 20. Authenticated proxy
$response = Curl::get('https://api.example.com/data')
    ->proxy('proxy.example.com', 8080, 'proxy_user', 'proxy_pass');

// ============================================
// COOKIE YÖNETİMİ
// ============================================

// 21. Cookie ekleme
$response = Curl::get('https://example.com')
    ->cookie('session_id', 'abc123xyz')
    ->cookie('user_token', 'token_value');

// 22. Cookie dosyası kullanma
$response = Curl::get('https://example.com/api')
    ->cookieFile(storage_path('cookies/session.txt'));

// ============================================
// RESPONSE İŞLEMLERİ
// ============================================

// 23. Response kontrolü
$response = Curl::get('https://api.example.com/data');

if ($response->successful()) {
    // 2xx response
    $data = $response->json();
    processData($data);
} elseif ($response->clientError()) {
    // 4xx response
    echo "Client hatası: " . $response->status();
} elseif ($response->serverError()) {
    // 5xx response
    echo "Server hatası: " . $response->status();
}

// 24. Detaylı hata kontrolü
$response = Curl::get('https://api.example.com/data');

if ($response->failed()) {
    $errorMessage = $response->error();
    $errorCode = $response->errno();
    
    Log::error("Curl hatası", [
        'message' => $errorMessage,
        'code' => $errorCode,
        'url' => 'https://api.example.com/data'
    ]);
}

// 25. Request bilgilerini alma
$response = Curl::get('https://api.example.com/data');

$info = $response->info();
echo "Total time: " . $info['total_time'] . " seconds\n";
echo "Download size: " . $info['size_download'] . " bytes\n";
echo "HTTP code: " . $info['http_code'] . "\n";

// Specific bilgi
$totalTime = $response->info('total_time');

// ============================================
// ZİNCİRLEME KULLANIM
// ============================================

// 26. Kompleks zincirleme örneği
$response = Curl::post('https://api.example.com/data')
    ->bearerToken('your-token')
    ->header('X-API-Version', '2.0')
    ->header('X-Request-ID', uniqid())
    ->timeout(30)
    ->sslVerify(true)
    ->followRedirects(true)
    ->json([
        'data' => [
            'name' => 'Test',
            'value' => 123
        ]
    ]);

// ============================================
// GERÇEKÇİ SENARYOLAR
// ============================================

// 27. Weather API örneği
$city = 'Istanbul';
$apiKey = 'your-api-key';

$weather = Curl::get('https://api.openweathermap.org/data/2.5/weather', [
    'q' => $city,
    'appid' => $apiKey,
    'units' => 'metric',
    'lang' => 'tr'
])->json();

if (isset($weather['main'])) {
    echo "Sıcaklık: " . $weather['main']['temp'] . "°C\n";
    echo "Hava Durumu: " . $weather['weather'][0]['description'];
}

// 28. GitHub API - Kullanıcı bilgileri
$username = 'zeydkazanci03';

$user = Curl::get("https://api.github.com/users/{$username}")
    ->header('Accept', 'application/vnd.github.v3+json')
    ->timeout(10)
    ->json();

echo "İsim: " . $user['name'] . "\n";
echo "Followers: " . $user['followers'] . "\n";
echo "Public Repos: " . $user['public_repos'];

// 29. REST API - CRUD İşlemleri
class UserAPI
{
    protected $baseUrl = 'https://api.example.com';
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function getUsers($page = 1)
    {
        return Curl::get("{$this->baseUrl}/users", ['page' => $page])
            ->bearerToken($this->token)
            ->json();
    }

    public function getUser($id)
    {
        return Curl::get("{$this->baseUrl}/users/{$id}")
            ->bearerToken($this->token)
            ->json();
    }

    public function createUser($data)
    {
        return Curl::post("{$this->baseUrl}/users")
            ->bearerToken($this->token)
            ->json($data)
            ->json();
    }

    public function updateUser($id, $data)
    {
        return Curl::put("{$this->baseUrl}/users/{$id}")
            ->bearerToken($this->token)
            ->json($data)
            ->json();
    }

    public function deleteUser($id)
    {
        return Curl::delete("{$this->baseUrl}/users/{$id}")
            ->bearerToken($this->token)
            ->successful();
    }
}

// Kullanım
$api = new UserAPI('your-api-token');
$users = $api->getUsers(1);

// 30. Webhook gönderimi
$webhookUrl = 'https://hooks.slack.com/services/YOUR/WEBHOOK/URL';

$response = Curl::post($webhookUrl)
    ->json([
        'text' => 'Yeni sipariş alındı!',
        'username' => 'E-Ticaret Bot',
        'icon_emoji' => ':shopping_cart:',
        'attachments' => [
            [
                'color' => 'good',
                'title' => 'Sipariş #12345',
                'fields' => [
                    [
                        'title' => 'Müşteri',
                        'value' => 'Zeyd Kazancı',
                        'short' => true
                    ],
                    [
                        'title' => 'Tutar',
                        'value' => '250 TL',
                        'short' => true
                    ]
                ]
            ]
        ]
    ]);

// ============================================
// HATA YÖNETİMİ
// ============================================

// 31. Try-catch ile hata yönetimi
try {
    $response = Curl::get('https://api.example.com/data')
        ->bearerToken('token')
        ->timeout(5);

    if ($response->successful()) {
        $data = $response->json();
        return $data;
    } else {
        throw new Exception("API hatası: " . $response->status());
    }
} catch (Exception $e) {
    Log::error('API isteği başarısız', [
        'error' => $e->getMessage(),
        'curl_error' => $response->error() ?? null
    ]);
    
    return null;
}

// 32. Retry mekanizması
function fetchWithRetry($url, $maxRetries = 3)
{
    $attempt = 0;
    
    while ($attempt < $maxRetries) {
        $response = Curl::get($url)->timeout(10);
        
        if ($response->successful()) {
            return $response->json();
        }
        
        $attempt++;
        
        if ($attempt < $maxRetries) {
            sleep(pow(2, $attempt)); // Exponential backoff
        }
    }
    
    throw new Exception("API isteği {$maxRetries} denemeden sonra başarısız oldu");
}

// ============================================
// Laravel SERVİS ENTEGRASYONU
// ============================================

// 33. Service sınıfı örneği
namespace App\Services;

use Curl;
use Illuminate\Support\Facades\Cache;

class ExternalAPIService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.external_api.url');
        $this->apiKey = config('services.external_api.key');
    }

    public function getData($endpoint, $params = [])
    {
        $cacheKey = "api_data_{$endpoint}_" . md5(json_encode($params));
        
        return Cache::remember($cacheKey, 3600, function () use ($endpoint, $params) {
            $response = Curl::get("{$this->baseUrl}/{$endpoint}", $params)
                ->header('X-API-Key', $this->apiKey)
                ->timeout(30);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception("API hatası: " . $response->error());
        });
    }
}

// 34. Controller'da kullanım
namespace App\Http\Controllers;

use Curl;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function fetchData(Request $request)
    {
        $response = Curl::get('https://api.example.com/data')
            ->bearerToken($request->user()->api_token)
            ->timeout(15);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $response->error()
        ], $response->status());
    }
}

// ============================================
// RATE LIMITING
// ============================================

// 35. Rate limiting örneği
class RateLimitedAPIClient
{
    protected $requestsPerMinute = 60;
    protected $lastRequestTime;

    protected function waitForRateLimit()
    {
        if ($this->lastRequestTime) {
            $elapsed = microtime(true) - $this->lastRequestTime;
            $minInterval = 60 / $this->requestsPerMinute;
            
            if ($elapsed < $minInterval) {
                usleep(($minInterval - $elapsed) * 1000000);
            }
        }
        
        $this->lastRequestTime = microtime(true);
    }

    public function makeRequest($url)
    {
        $this->waitForRateLimit();
        
        return Curl::get($url)
            ->bearerToken('token')
            ->json();
    }
}
