<?php

namespace ZeydKazanci03\LaraCurl;

use Illuminate\Support\Facades\Log;

class CurlManager
{
    protected $ch;
    protected $url;
    protected $method = 'GET';
    protected $headers = [];
    protected $data = [];
    protected $options = [];
    protected $response;
    protected $responseHeaders = [];
    protected $responseBody;
    protected $info;
    protected $error;
    protected $errno;
    protected $timeout = 30;
    protected $followRedirects = true;
    protected $maxRedirects = 5;
    protected $userAgent = 'LaraCurl/1.0';
    protected $cookies = [];
    protected $cookieFile;
    protected $proxy;
    protected $proxyAuth;
    protected $authentication;
    protected $sslVerify = true;
    protected $returnTransfer = true;
    protected $verbose = false;

    public function __construct()
    {
        $this->ch = curl_init();
        $this->setDefaultOptions();
    }

    /**
     * Varsayılan seçenekleri ayarla
     */
    protected function setDefaultOptions()
    {
        $this->options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => $this->followRedirects,
            CURLOPT_MAXREDIRS => $this->maxRedirects,
            CURLOPT_USERAGENT => $this->userAgent,
            CURLOPT_SSL_VERIFYPEER => $this->sslVerify,
            CURLOPT_SSL_VERIFYHOST => $this->sslVerify ? 2 : 0,
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
        ];
    }

    /**
     * URL ayarla
     */
    public function url($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * GET isteği
     */
    public function get($url = null, $data = [])
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'GET';

        if (!empty($data)) {
            $separator = strpos($this->url, '?') !== false ? '&' : '?';
            $this->url .= $separator . http_build_query($data);
        }

        return $this->execute();
    }

    /**
     * POST isteği
     */
    public function post($url = null, $data = [])
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'POST';

        // Sadece parametre olarak data gelirse set et
        // asJson() gibi metodlar zaten $this->data'yı set etmiştir
        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->execute();
    }

    /**
     * PUT isteği
     */
    public function put($url = null, $data = [])
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'PUT';

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->execute();
    }

    /**
     * PATCH isteği
     */
    public function patch($url = null, $data = [])
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'PATCH';

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->execute();
    }

    /**
     * DELETE isteği
     */
    public function delete($url = null, $data = [])
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'DELETE';

        if (!empty($data)) {
            $this->data = $data;
        }

        return $this->execute();
    }

    /**
     * HEAD isteği
     */
    public function head($url = null)
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'HEAD';
        $this->options[CURLOPT_NOBODY] = true;

        return $this->execute();
    }

    /**
     * OPTIONS isteği
     */
    public function options($url = null)
    {
        if ($url) {
            $this->url($url);
        }

        $this->method = 'OPTIONS';

        return $this->execute();
    }

    /**
     * Header ekle
     */
    public function header($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->headers[] = "$k: $v";
            }
        } else {
            $this->headers[] = "$key: $value";
        }

        return $this;
    }

    /**
     * Çoklu header ekle
     */
    public function headers(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->header($key, $value);
        }

        return $this;
    }

    /**
     * Bearer token ekle
     */
    public function bearerToken($token)
    {
        return $this->header('Authorization', 'Bearer ' . $token);
    }

    /**
     * Basic Authentication
     */
    public function basicAuth($username, $password)
    {
        $this->authentication = [
            'type' => 'basic',
            'username' => $username,
            'password' => $password
        ];

        return $this;
    }

    /**
     * Digest Authentication
     */
    public function digestAuth($username, $password)
    {
        $this->authentication = [
            'type' => 'digest',
            'username' => $username,
            'password' => $password
        ];

        return $this;
    }

    /**
     * Timeout ayarla (saniye)
     */
    public function timeout($seconds)
    {
        $this->timeout = $seconds;
        $this->options[CURLOPT_TIMEOUT] = $seconds;

        return $this;
    }

    /**
     * Connect timeout ayarla
     */
    public function connectTimeout($seconds)
    {
        $this->options[CURLOPT_CONNECTTIMEOUT] = $seconds;

        return $this;
    }

    /**
     * User Agent ayarla
     */
    public function userAgent($userAgent)
    {
        $this->userAgent = $userAgent;
        $this->options[CURLOPT_USERAGENT] = $userAgent;

        return $this;
    }

    /**
     * Referer ayarla
     */
    public function referer($referer)
    {
        $this->options[CURLOPT_REFERER] = $referer;

        return $this;
    }

    /**
     * SSL doğrulamayı aç/kapat
     */
    public function sslVerify($verify = true)
    {
        $this->sslVerify = $verify;
        $this->options[CURLOPT_SSL_VERIFYPEER] = $verify;
        $this->options[CURLOPT_SSL_VERIFYHOST] = $verify ? 2 : 0;

        return $this;
    }

    /**
     * Yönlendirmeleri takip et
     */
    public function followRedirects($follow = true, $maxRedirects = 5)
    {
        $this->followRedirects = $follow;
        $this->maxRedirects = $maxRedirects;
        $this->options[CURLOPT_FOLLOWLOCATION] = $follow;
        $this->options[CURLOPT_MAXREDIRS] = $maxRedirects;

        return $this;
    }

    /**
     * Cookie ekle
     */
    public function cookie($name, $value)
    {
        $this->cookies[$name] = $value;

        return $this;
    }

    /**
     * Cookie dosyası ayarla
     */
    public function cookieFile($file)
    {
        $this->cookieFile = $file;
        $this->options[CURLOPT_COOKIEFILE] = $file;
        $this->options[CURLOPT_COOKIEJAR] = $file;

        return $this;
    }

    /**
     * Proxy ayarla
     */
    public function proxy($proxy, $port = null, $username = null, $password = null)
    {
        $this->proxy = $port ? "$proxy:$port" : $proxy;
        $this->options[CURLOPT_PROXY] = $this->proxy;

        if ($username && $password) {
            $this->proxyAuth = "$username:$password";
            $this->options[CURLOPT_PROXYUSERPWD] = $this->proxyAuth;
        }

        return $this;
    }

    /**
     * JSON gönder (Request için)
     */
    public function asJson($data)
    {
        $this->data = $data;
        $this->header('Content-Type', 'application/json');

        return $this;
    }

    /**
     * Form data gönder
     */
    public function asForm($data)
    {
        $this->data = $data;
        $this->header('Content-Type', 'application/x-www-form-urlencoded');

        return $this;
    }

    /**
     * Multipart form data gönder
     */
    public function asMultipart($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Dosya yükle
     */
    public function attach($fieldName, $filePath, $mimeType = null, $postFilename = null)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("Dosya bulunamadı: $filePath");
        }

        $curlFile = curl_file_create($filePath, $mimeType, $postFilename);

        if (!is_array($this->data)) {
            $this->data = [];
        }

        $this->data[$fieldName] = $curlFile;

        return $this;
    }

    /**
     * Verbose mode
     */
    public function verbose($verbose = true)
    {
        $this->verbose = $verbose;
        $this->options[CURLOPT_VERBOSE] = $verbose;

        return $this;
    }

    /**
     * Custom CURL option ekle
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;

        return $this;
    }

    /**
     * Çoklu custom option ekle
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option => $value) {
            $this->options[$option] = $value;
        }

        return $this;
    }

    /**
     * Response object döndür
     */
    public function returnResponseObject()
    {
        $this->options['return_response_object'] = true;
        return $this;
    }

    /**
     * Response object döndür
     */
    public function returnResponseHeader()
    {
        return $this->responseHeaders;
    }

    /**
     * İsteği çalıştır
     */
    protected function execute()
    {
        // URL kontrolü
        if (empty($this->url)) {
            throw new \Exception('URL belirtilmedi');
        }

        // URL'i ayarla
        curl_setopt($this->ch, CURLOPT_URL, $this->url);

        // Authentication ayarla
        if ($this->authentication) {
            if ($this->authentication['type'] === 'basic') {
                curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($this->ch, CURLOPT_USERPWD, $this->authentication['username'] . ':' . $this->authentication['password']);
            } elseif ($this->authentication['type'] === 'digest') {
                curl_setopt($this->ch, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
                curl_setopt($this->ch, CURLOPT_USERPWD, $this->authentication['username'] . ':' . $this->authentication['password']);
            }
        }

        // Data ayarla
        $postData = null;
        if (!empty($this->data)) {
            $contentType = $this->getContentType();

            if ($contentType === 'application/json') {
                // JSON encode et
                $postData = is_string($this->data) ? $this->data : json_encode($this->data);

                // Content-Length otomatik ekle
                if (!$this->hasHeader('Content-Length')) {
                    $this->header('Content-Length', strlen($postData));
                }

            } elseif ($contentType === 'application/x-www-form-urlencoded') {
                // URL encode et
                $postData = is_array($this->data) ? http_build_query($this->data) : $this->data;

                // Content-Length otomatik ekle
                if (!$this->hasHeader('Content-Length')) {
                    $this->header('Content-Length', strlen($postData));
                }

            } else {
                // Multipart veya diğer
                $postData = $this->data;
            }

            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
        }

        // HTTP metodunu ayarla
        if ($this->method === 'POST') {
            curl_setopt($this->ch, CURLOPT_POST, true);
        } elseif ($this->method === 'GET') {
            curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->method);
        }

        // Headers ayarla
        if (!empty($this->headers)) {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);
        }

        // Cookies ayarla
        if (!empty($this->cookies)) {
            $cookieString = [];
            foreach ($this->cookies as $name => $value) {
                $cookieString[] = "$name=$value";
            }
            curl_setopt($this->ch, CURLOPT_COOKIE, implode('; ', $cookieString));
        }

        // Tüm seçenekleri uygula
        foreach ($this->options as $option => $value) {
            if ($option !== 'return_response_object') {
                curl_setopt($this->ch, $option, $value);
            }
        }

        // İsteği çalıştır
        $this->response = curl_exec($this->ch);
        $this->info = curl_getinfo($this->ch);
        $this->errno = curl_errno($this->ch);
        $this->error = curl_error($this->ch);

        // Header ve body'yi ayır
        $headerSize = $this->info['header_size'] ?? 0;
        $headerString = substr($this->response, 0, $headerSize);
        $this->responseBody = substr($this->response, $headerSize);

        // Header'ları parse et
        $this->parseHeaders($headerString);

        // Response object istendiyse döndür
        if (isset($this->options['return_response_object']) && $this->options['return_response_object']) {
            return (object) [
                'body' => $this->responseBody,
                'headers' => $this->responseHeaders,
                'status' => $this->status(),
                'info' => $this->info,
                'error' => $this->error,
                'errno' => $this->errno
            ];
        }

        return $this;
    }

    /**
     * Header'ları parse et
     */
    protected function parseHeaders($headerString)
    {
        $headers = [];
        $lines = explode("\r\n", $headerString);

        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $headers[trim($key)] = trim($value);
            }
        }

        $this->responseHeaders = $headers;
    }

    /**
     * Content-Type başlığını al
     */
    protected function getContentType()
    {
        foreach ($this->headers as $header) {
            if (stripos($header, 'Content-Type:') === 0) {
                return trim(substr($header, strpos($header, ':') + 1));
            }
        }
        return null;
    }

    /**
     * Belirli bir header var mı kontrol et
     */
    protected function hasHeader($headerName)
    {
        foreach ($this->headers as $header) {
            if (stripos($header, $headerName . ':') === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Response body'yi al
     */
    public function response()
    {
        return $this->responseBody ?? $this->response;
    }

    /**
     * Response'u JSON olarak al (Response için)
     */
    public function json()
    {
        return json_decode($this->responseBody ?? $this->response, true);
    }

    /**
     * Response'u obje olarak al
     */
    public function object()
    {
        return json_decode($this->responseBody ?? $this->response);
    }

    /**
     * HTTP status code al
     */
    public function status()
    {
        return $this->info['http_code'] ?? null;
    }

    /**
     * İstek bilgilerini al
     */
    public function info($key = null)
    {
        if ($key) {
            return $this->info[$key] ?? null;
        }
        return $this->info;
    }

    /**
     * Hata mesajını al
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Hata numarasını al
     */
    public function errno()
    {
        return $this->errno;
    }

    /**
     * İstek başarılı mı?
     */
    public function successful()
    {
        $status = $this->status();
        return $status >= 200 && $status < 300;
    }

    /**
     * İstek başarısız mı?
     */
    public function failed()
    {
        return !$this->successful();
    }

    /**
     * Client hatası mı? (4xx)
     */
    public function clientError()
    {
        $status = $this->status();
        return $status >= 400 && $status < 500;
    }

    /**
     * Server hatası mı? (5xx)
     */
    public function serverError()
    {
        $status = $this->status();
        return $status >= 500;
    }

    /**
     * Response header'larını al
     */
    public function getHeaders()
    {
        return $this->responseHeaders;
    }

    /**
     * Belirli bir header değeri al
     */
    public function getHeader($key)
    {
        return $this->responseHeaders[$key] ?? null;
    }

    /**
     * Response'u dosyaya kaydet
     */
    public function save($filePath)
    {
        return file_put_contents($filePath, $this->responseBody ?? $this->response);
    }

    /**
     * Response'u indir
     */
    public function download($filename = null)
    {
        if (!$filename) {
            $filename = basename(parse_url($this->url, PHP_URL_PATH));
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($this->responseBody ?? $this->response));

        echo $this->responseBody ?? $this->response;
        exit;
    }

    /**
     * Curl handle'ı kapat
     */
    public function close()
    {
        if ($this->ch) {
            curl_close($this->ch);
            $this->ch = null;
        }
    }

    /**
     * Yeni bir instance oluştur
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Response'u string olarak döndür
     */
    public function __toString()
    {
        return (string) ($this->responseBody ?? $this->response);
    }
}
