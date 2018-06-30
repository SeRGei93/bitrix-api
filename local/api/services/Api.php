<?php

namespace app\services;

class Api
{
    public static $params;
    public static $gets;

    public static function getParams()
    {
        static::$params = include PARAMS_FILENAME;
    }

    public static function run()
    {
        static::getParams();
        Helpers::defineApiOperation();
        Input::validate();

        if (static::$gets['current'] > 0) {
            Ajax::bitrix();
        } elseif (API_OPERATION == "delete" AND static::$gets['process'] == "") {

            if (static::$gets['element_id'] != 0) {
                // Удаление только 1 элемента
                $iblock_id = \CIBlockElement::GetIBlockByID(static::$gets['element_id']);

                if (!$iblock_id) {
                    $strLog = "Указанный элемент № " . static::$gets['element_id'] . " в БД не существует!";
                    Log::saveToFile($strLog);
                    Render::message($strLog, "danger");
                }

                static::$gets['IBLOCK'] = $iblock_id;
                static::$gets['iblock_ids'] = $iblock_id;

                Scripts::$variables ['arrayCount'] = [
                    'type' => 'array',
                    'value' => \CIBlock::GetElementCount($iblock_id)
                ];

            } else {
                // Проверяем существование группы ИБ для удаления
                $arrayIB = Helpers::getExistIBs(static::$gets['iblock_ids']);

                if (count($arrayIB) > 0) {
                    static::$gets['iblock_ids'] = implode(",", $arrayIB);

                    $arrayCount = Array();
                    // Количество элементов в инфоблоках для JS
                    foreach ($arrayIB as $key => $iblock_id) {
                        $arrayCount[$key] = \CIBlock::GetElementCount($iblock_id);
                    }
                    Scripts::$variables ['arrayCount'] = [
                        'type' => 'array',
                        'value' => $arrayCount
                    ];

                    $iblock_id = $arrayIB[0];
                    static::$gets['IBLOCK'] = $iblock_id;

                } else {
                    Render::message("Нет существующих инфоблоков", "danger");
                }

            }

            if (static::$gets['STEP'] == 0) static::$gets['STEP'] = API_STEP;

            Scripts::$variables ['fullUrl'] = ['type' => 'string', 'value' => Helpers::getUrlWithParams()];

        }
        Render::message("Процесс " . static::$params[API_OPERATION]['message'] . " начат...", "info");
    }
}