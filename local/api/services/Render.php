<?php

namespace app\services;

class Render
{
    // Вывод шаблона
    static public function showTemplate($message)
    {
        include(VIEWS_DIR . "template.php");
    }

    // Вывод информационного сообщения
    static public function message($text, $alert)
    {
        $message = "<div class='alert alert-$alert' role='alert'>$text</div>";
        self::showTemplate($message);
        exit;
    }
}