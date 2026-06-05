<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

require_once 'config.php'; // โหลด .env ผ่าน config.php ปกติ

echo json_encode([
    'ai_mode' => $AI_MODE,
    'ai_model' => $AI_MODE == 0 ? $AI_config['model'] : $AI_EXTERNAL_config['model']
], JSON_UNESCAPED_UNICODE);