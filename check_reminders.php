<?php

require_once("connect.php");
require_once("functions.php");

$config     = parse_ini_file('config.ini');
$token      = $config['token'];
$website    = "https://api.telegram.org/bot$token";

$dateTime       = new DateTime();
$date           = $dateTime->format('Y-m-d');
$initialTime    = $dateTime->modify('+10 hours'); // Correção fuso horário
$initialTime    = $dateTime->format('h:i');
$finalTime      = $dateTime->modify('+10 hours');  // Correção fuso horário
$finalTime      = $dateTime->modify('+10 minutes');
$finalTime      = $dateTime->format('h:i');

$reminders      = checkReminders($date, $initialTime, $finalTime, $conn);

if (count($reminders)) {
    foreach ($reminders as $reminder) {
        $chatId = $reminder['chat_id'];
        $content = $reminder['content'];
        sendMessage($chatId, $content);
    }
}
