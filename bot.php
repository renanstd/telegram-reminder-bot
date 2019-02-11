<?php

date_default_timezone_set('America/Sao_Paulo');

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot".$token;

// Receber o POST do webhook
$updates    = file_get_contents("php://input");
$updates    = json_decode($updates, TRUE);

// Identificar a mensagem e o id do chat
$text       = $updates['message']['text'];
$chatId     = $updates['message']['chat']['id'];

// Verificar se não é o comando getLog (uso do admin)
if (strpos($text, '/getlog') !== false) {
    $response = getLog();
    sendMessage($chatId, $response);
    die;
}

// Reconhecer os elementos chaves do lembrete
$data       = recognizer($text);
$response   = "";

// Verificar resposta e armazenar
if ($data) {
    extract($data);
    $response = "Lembrete adicionado: \n'$reminder', \ndia $date,\n às $hour.";
    saveReminder($chatId, $data, $conn);
} else {
    $response = "Não conseguimos identificar o que você disse.";
}

// Enviar a mensagem
sendMessage($chatId, $response);
