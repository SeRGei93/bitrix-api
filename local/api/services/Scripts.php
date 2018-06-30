<?php

namespace app\services;

class Scripts
{

    // Переменные JS
    public static $variables = [
        'fullUrl' => ['type' => 'string', 'value' => ''],
        'arrayCount' => ['type' => 'array', 'value' => '']
    ];

    // Вывод JS в template
    public static function view()
    {
        echo "<script>" . PHP_EOL .
            static::build() .
            "</script>" . PHP_EOL;
    }

    // Формирование переменных из массива
    public static function build()
    {
        $resultJS = "var ";
        $afterJS = "";

        foreach (static::$variables as $key => $variable) {
            if ($variable['type'] == "int" AND $variable['value'] != "") {
                $resultJS .= $key . " = Number(" . $variable['value'] . "),";
            } elseif ($variable['type'] == "string" AND $variable['value'] != "") {
                $resultJS .= $key . " = String('" . $variable['value'] . "'),";
            } elseif ($variable['type'] == "array") {
                $resultJS .= $key . " = [],";
                if ($variable['value'] != "") {
                    if (count($variable['value']) == 1) {
                        $afterJS .= $key . "[0] = " . $variable['value'] . ";" . PHP_EOL;
                    } else {
                        foreach ($variable['value'] as $i => $value) {
                            $afterJS .= $key . "[" . $i . "] = " . $value . ";" . PHP_EOL;
                        }
                    }
                }
            } else {
                $resultJS .= $key . ",";
            }

            $resultJS .= PHP_EOL;
        }

        // Удаляем последнюю запятую
        $resultJS = substr($resultJS, 0, (strlen(PHP_EOL) + 1) * -1) . ";" . PHP_EOL . $afterJS;
        return $resultJS;
    }

}

