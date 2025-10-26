<?php

namespace ZeydKazanci03\LaraCurl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ZeydKazanci03\LaraCurl\CurlManager url(string $url)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager get(string $url = null, array $data = [])
 * @method static \ZeydKazanci03\LaraCurl\CurlManager post(string $url = null, array $data = [])
 * @method static \ZeydKazanci03\LaraCurl\CurlManager put(string $url = null, array $data = [])
 * @method static \ZeydKazanci03\LaraCurl\CurlManager patch(string $url = null, array $data = [])
 * @method static \ZeydKazanci03\LaraCurl\CurlManager delete(string $url = null, array $data = [])
 * @method static \ZeydKazanci03\LaraCurl\CurlManager head(string $url = null)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager options(string $url = null)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager header(string|array $key, string $value = null)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager headers(array $headers)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager bearerToken(string $token)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager basicAuth(string $username, string $password)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager digestAuth(string $username, string $password)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager timeout(int $seconds)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager connectTimeout(int $seconds)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager userAgent(string $userAgent)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager referer(string $referer)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager sslVerify(bool $verify = true)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager followRedirects(bool $follow = true, int $maxRedirects = 5)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager cookie(string $name, string $value)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager cookieFile(string $file)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager proxy(string $proxy, int $port = null, string $username = null, string $password = null)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager json(array|string $data)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager form(array $data)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager multipart(array $data)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager attach(string $fieldName, string $filePath, string $mimeType = null, string $postFilename = null)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager verbose(bool $verbose = true)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager setOption(int $option, mixed $value)
 * @method static \ZeydKazanci03\LaraCurl\CurlManager setOptions(array $options)
 * @method static string response()
 * @method static array|null json()
 * @method static object|null object()
 * @method static int|null status()
 * @method static array|mixed info(string $key = null)
 * @method static string error()
 * @method static int errno()
 * @method static bool successful()
 * @method static bool failed()
 * @method static bool clientError()
 * @method static bool serverError()
 * @method static array headers()
 * @method static int|false save(string $filePath)
 * @method static void download(string $filename = null)
 * @method static void close()
 * @method static \ZeydKazanci03\LaraCurl\CurlManager make()
 *
 * @see \ZeydKazanci03\LaraCurl\CurlManager
 */
class Curl extends Facade
{
    /**
     * Facade'in bağlı olduğu servis adını döndür
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laracurl';
    }
}
