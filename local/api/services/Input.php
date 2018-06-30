<?php

namespace app\services;

class Input
{
    // Проверка APIKEY
    public static function validateApiKey($request)
    {
        if (empty($request["apikey"]) OR $request["apikey"] != Api::$params['apikey']) {
            Render::message("Access Denied!", "danger");
        }
    }

    // Валидация GET-параметров, удаление инъекций
    public static function validateGets($request)
    {
        foreach (Api::$params[API_OPERATION]['get'] as $key => $param) {
            if (empty($request[$key]) AND $param['require'] == true) {
                Render::message("Не хватает необходимых значений! Проверьте правильность названий и регистр!", "danger");
            }

            Api::$gets[$key] = $request[$key];

            if ($param['type'] == "int") {
                Api::$gets[$key] = (int)Api::$gets[$key];
                if (Api::$gets[$key] <= 0 AND $param['require'] == true) {
                    Render::message("Значения не могут быть отрицательными или нулевыми!", "danger");
                }
            } elseif ($param['type'] == "string") {
                Api::$gets[$key] = htmlspecialchars((string)Api::$gets[$key]);
            }
        }
    }

    // Валидация
    public static function validate()
    {
        // API функционал для всех Ajax запросов
        $request = \Bitrix\Main\HttpApplication::getInstance()->getContext()->getRequest();

        self::validateApiKey($request);
        self::validateGets($request);
    }
}