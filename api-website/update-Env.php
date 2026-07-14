<?php

$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $key => $value) {
    updateEnv($key, $value);
}

echo json_encode([
    "status" => true
]);


function updateEnv($key, $value, $path = '.env')
{
    $content = file_get_contents($path);

    $pattern = "/^{$key}=.*/m";

    if (preg_match($pattern, $content)) {
        $content = preg_replace(
            $pattern,
            "{$key}={$value}",
            $content
        );
    } else {
        $content .= PHP_EOL . "{$key}={$value}";
    }

    file_put_contents($path, $content);
}
