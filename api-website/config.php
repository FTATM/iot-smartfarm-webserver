<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$AI_MODE = (int)($_ENV['AI_MODE'] ?? 0);

$AI_config = [
    'model' => $_ENV['AI_MODEL'],
    'api_url' => $_ENV['AI_API_URL']
];

$AI_EXTERNAL_config = [
    'model' => $_ENV['AI_EXTERNAL_MODEL'],
    'api_url' => $_ENV['AI_EXTERNAL_URL'],
    'api_key' => $_ENV['AI_EXTERNAL_KEY']
];

$TMD_config = [
    'api_url' => $_ENV['TMD_API_URL'],
    'token'   => $_ENV['TMD_API_KEY'],
    'rain_cond_threshold' => $_ENV['RAIN_THRESHOLD']
];

$MAIL_config = [
    'host' => $_ENV['MAIL_HOST'],
    'port' => $_ENV['MAIL_PORT'],
    'encryption' => $_ENV['MAIL_ENCRYPTION'],
    'username' => $_ENV['MAIL_USERNAME'],
    'password' => $_ENV['MAIL_PASSWORD']
];

$LINE_config = [
    'token' => $_ENV['LINE_NOTIFY_TOKEN']
];

$SMS_config = [
    'url'        => $_ENV['SMS_API_URL'],
    'api_key'    => $_ENV['SMS_API_KEY'],
    'api_secret' => $_ENV['SMS_API_SECRET'],
    'sender'     => $_ENV['SMS_SENDER'],
];

const ENV_PUBLIC_KEYS = [
    'AI_MODE',
    'AI_MODEL',
    'AI_API_URL',
    'AI_EXTERNAL_MODEL',
    'AI_EXTERNAL_URL',
    'RAIN_THRESHOLD',
    'APP_ENV',
    'APP_DEBUG'
];

// =============================
// update env function flexible
// =============================

function updateEnvValue($key, $value)
{
    $envFile = __DIR__ . '/../.env';

    $content = file_get_contents($envFile);

    if ($content === false) {
        return false;
    }

    $pattern = "/^" . preg_quote($key, '/') . "=.*$/m";

    if (preg_match($pattern, $content)) {
        $content = preg_replace($pattern, "{$key}={$value}", $content);
    } else {
        $content .= PHP_EOL . "{$key}={$value}";
    }

    if (file_put_contents($envFile, $content) !== false) {
        $_ENV[$key] = $value;   // อัปเดตค่าที่โหลดไว้ด้วย
        return true;
    }

    return false;
}

function getEnvValue($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

function getEnvs(array $keys)
{
    $result = [];

    foreach ($keys as $key) {
        $result[$key] = $_ENV[$key] ?? null;
    }

    return $result;
}
