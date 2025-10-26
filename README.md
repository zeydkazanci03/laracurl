# LaraCurl

Laravel 10+ için güçlü ve kullanımı kolay Curl paketi.

## Özellikler

- ✅ Tüm HTTP metodları (GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS)
- ✅ Fluent API ile kolay kullanım
- ✅ JSON, Form Data, Multipart desteği
- ✅ Dosya yükleme
- ✅ Bearer Token ve Basic/Digest Authentication
- ✅ Proxy desteği
- ✅ Cookie yönetimi
- ✅ SSL doğrulama kontrolü
- ✅ Header yönetimi
- ✅ Timeout ayarları
- ✅ Yönlendirme kontrolü
- ✅ Response helper metodları
- ✅ Laravel Facade desteği

## Kurulum

Composer ile paketi yükleyin:

```bash
composer require zeydkazanci03/laracurl
```

Laravel 10+ için otomatik olarak servis sağlayıcı kaydedilir.

### Config Dosyasını Yayınlama (Opsiyonel)

```bash
php artisan vendor:publish --tag=laracurl-config
```

## Temel Kullanım

### GET İsteği

```php
use Curl;

// Basit GET isteği
$response = Curl::get('https://api.example.com/users');
echo $response->response();

// Query parametreleri ile
$response = Curl::get('https://api.example.com/users', [
    'page' => 1,
    'limit' => 10
]);

// JSON olarak al
$data = $response->json();
```

### POST İsteği

```php
// JSON gönderme
$response = Curl::post('https://api.example.com/users')
    ->json([
        'name' => 'Zeyd Kazancı',
        'email' => 'zeyd@example.com'
    ])
    ->response();

// Form data gönderme
$response = Curl::post('https://api.example.com/login')
    ->form([
        'username' => 'zeyd',
        'password' => 'secret'
    ])
    ->response();
```

### PUT/PATCH İsteği

```php
$response = Curl::put('https://api.example.com/users/1')
    ->json([
        'name' => 'Yeni İsim'
    ])
    ->response();

$response = Curl::patch('https://api.example.com/users/1')
    ->json([
        'email' => 'yeni@example.com'
    ])
    ->response();
```

### DELETE İsteği

```php
$response = Curl::delete('https://api.example.com/users/1');

if ($response->successful()) {
    echo "Başarıyla silindi";
}
```

## Gelişmiş Kullanım

### Header Yönetimi

```php
$response = Curl::get('https://api.example.com/data')
    ->header('X-Custom-Header', 'value')
    ->headers([
        'X-Another-Header' => 'value',
        'X-Third-Header' => 'value'
    ])
    ->response();
```

### Authentication

#### Bearer Token

```php
$response = Curl::get('https://api.example.com/protected')
    ->bearerToken('your-token-here')
    ->response();
```

#### Basic Authentication

```php
$response = Curl::get('https://api.example.com/protected')
    ->basicAuth('username', 'password')
    ->response();
```

#### Digest Authentication

```php
$response = Curl::get('https://api.example.com/protected')
    ->digestAuth('username', 'password')
    ->response();
```

### Dosya Yükleme

```php
$response = Curl::post('https://api.example.com/upload')
    ->attach('file', '/path/to/file.jpg', 'image/jpeg')
    ->attach('document', '/path/to/document.pdf')
    ->response();

// Ek form verileri ile
$response = Curl::post('https://api.example.com/upload')
    ->attach('avatar', '/path/to/avatar.jpg')
    ->multipart([
        'user_id' => 123,
        'description' => 'Profil fotoğrafı'
    ])
    ->response();
```

### Timeout Ayarları

```php
$response = Curl::get('https://api.example.com/slow-endpoint')
    ->timeout(60) // 60 saniye
    ->connectTimeout(10) // Bağlantı için 10 saniye
    ->response();
```

### SSL Doğrulama

```php
// SSL doğrulamayı kapat (development için)
$response = Curl::get('https://self-signed-cert.example.com')
    ->sslVerify(false)
    ->response();
```

### Proxy Kullanımı

```php
$response = Curl::get('https://api.example.com/data')
    ->proxy('proxy.example.com', 8080)
    ->response();

// Authentication ile
$response = Curl::get('https://api.example.com/data')
    ->proxy('proxy.example.com', 8080, 'username', 'password')
    ->response();
```

### Cookie Yönetimi

```php
// Cookie ekleme
$response = Curl::get('https://example.com')
    ->cookie('session_id', 'abc123')
    ->cookie('user_token', 'xyz789')
    ->response();

// Cookie dosyası kullanma
$response = Curl::get('https://example.com')
    ->cookieFile('/path/to/cookies.txt')
    ->response();
```

### Yönlendirme Kontrolü

```php
// Yönlendirmeleri takip etme
$response = Curl::get('https://example.com/redirect')
    ->followRedirects(true, 10) // Maksimum 10 yönlendirme
    ->response();

// Yönlendirmeleri devre dışı bırakma
$response = Curl::get('https://example.com/redirect')
    ->followRedirects(false)
    ->response();
```

### User Agent ve Referer

```php
$response = Curl::get('https://example.com')
    ->userAgent('MyCustomBot/1.0')
    ->referer('https://google.com')
    ->response();
```

### Custom CURL Options

```php
$response = Curl::get('https://example.com')
    ->setOption(CURLOPT_ENCODING, 'gzip')
    ->setOptions([
        CURLOPT_BUFFERSIZE => 4096,
        CURLOPT_FRESH_CONNECT => true
    ])
    ->response();
```

## Response Metodları

### Response Body

```php
$response = Curl::get('https://api.example.com/data');

// Ham response
$body = $response->response();

// JSON olarak
$data = $response->json();

// Obje olarak
$object = $response->object();

// String olarak
$string = (string) $response;
```

### Status Kontrolü

```php
$response = Curl::get('https://api.example.com/data');

// HTTP status code
$statusCode = $response->status(); // 200, 404, 500, vb.

// Başarılı mı? (2xx)
if ($response->successful()) {
    echo "Başarılı!";
}

// Başarısız mı?
if ($response->failed()) {
    echo "Başarısız!";
}

// Client hatası mı? (4xx)
if ($response->clientError()) {
    echo "Client hatası";
}

// Server hatası mı? (5xx)
if ($response->serverError()) {
    echo "Server hatası";
}
```

### Request Bilgileri

```php
$response = Curl::get('https://api.example.com/data');

// Tüm bilgiler
$info = $response->info();

// Belirli bir bilgi
$totalTime = $response->info('total_time');
$downloadSize = $response->info('size_download');

// Response header'ları
$headers = $response->headers();
```

### Hata Yönetimi

```php
$response = Curl::get('https://api.example.com/data');

if ($response->failed()) {
    $errorMessage = $response->error();
    $errorNumber = $response->errno();
    
    echo "Hata: $errorMessage (Kod: $errorNumber)";
}
```

### Dosyaya Kaydetme

```php
$response = Curl::get('https://example.com/file.pdf');

// Dosyaya kaydet
$response->save('/path/to/save/file.pdf');

// Tarayıcıdan indir
$response->download('my-file.pdf');
```

## Zincirleme Kullanım (Method Chaining)

```php
$response = Curl::post('https://api.example.com/data')
    ->bearerToken('your-token')
    ->header('X-Custom-Header', 'value')
    ->timeout(30)
    ->sslVerify(false)
    ->json([
        'key' => 'value'
    ]);

if ($response->successful()) {
    $data = $response->json();
    // İşlemler...
}
```

## Facade Olmadan Kullanım

```php
use ZeydKazanci03\LaraCurl\CurlManager;

$curl = new CurlManager();
$response = $curl->get('https://api.example.com/data');
```

## Bağımlılık Enjeksiyonu

```php
use ZeydKazanci03\LaraCurl\CurlManager;

class UserService
{
    protected $curl;
    
    public function __construct(CurlManager $curl)
    {
        $this->curl = $curl;
    }
    
    public function getUsers()
    {
        return $this->curl
            ->get('https://api.example.com/users')
            ->json();
    }
}
```

## Örnekler

### API'den Veri Çekme

```php
use Curl;

$response = Curl::get('https://jsonplaceholder.typicode.com/posts/1');

if ($response->successful()) {
    $post = $response->json();
    echo $post['title'];
}
```

### API'ye Veri Gönderme

```php
$response = Curl::post('https://jsonplaceholder.typicode.com/posts')
    ->json([
        'title' => 'Yeni Post',
        'body' => 'Post içeriği',
        'userId' => 1
    ]);

if ($response->successful()) {
    $newPost = $response->json();
    echo "Yeni post ID: " . $newPost['id'];
}
```

### Kimlik Doğrulama ile İstek

```php
$response = Curl::get('https://api.github.com/user')
    ->bearerToken('your-github-token');

if ($response->successful()) {
    $user = $response->json();
    echo "Merhaba, " . $user['name'];
}
```

### Dosya İndirme

```php
$response = Curl::get('https://example.com/large-file.zip')
    ->timeout(300); // 5 dakika

if ($response->successful()) {
    $response->save(storage_path('downloads/file.zip'));
    echo "Dosya indirildi!";
}
```

### Form ile Dosya Yükleme

```php
$response = Curl::post('https://api.example.com/upload')
    ->attach('image', storage_path('temp/photo.jpg'), 'image/jpeg')
    ->multipart([
        'title' => 'Tatil Fotoğrafı',
        'description' => '2024 yaz tatili',
        'category' => 'personal'
    ]);

if ($response->successful()) {
    echo "Dosya yüklendi!";
}
```

## Lisans

MIT License

## Destek

Herhangi bir sorun veya öneriniz için GitHub'da issue açabilirsiniz.

## Geliştirici

**Zeyd Kazancı**
- GitHub: [@zeydkazanci03](https://github.com/zeydkazanci03)

---

**LaraCurl** ile Laravel projelerinizde HTTP isteklerini kolayca yönetin! 🚀
