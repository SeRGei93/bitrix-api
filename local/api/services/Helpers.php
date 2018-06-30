<?php

namespace app\services;

class Helpers
{
    // Собираем URL c параметрами
    static public function getUrlWithParams()
    {
        $url = ROOT_HOST . $_SERVER['SCRIPT_NAME'] . "?";
        foreach (Api::$gets as $key => $param) {
            $url = $url . $key . "=" . $param . "&";
        }
        $url = $url . "apikey=" . Api::$params['apikey'];
        return $url;
    }

    // Определение операции в зависимости от вызываемого скрипта
    static public function defineApiOperation()
    {
        if (stristr($_SERVER['SCRIPT_NAME'], "api_add.php")) {
            $apiOperation = "add";
        }
        if (stristr($_SERVER['SCRIPT_NAME'], "api_delete.php")) {
            $apiOperation = "delete";
        }
        if (!isset($apiOperation)) {
            Render::message("Access Denied!", "danger");
        }
        define("API_OPERATION", $apiOperation);
    }

    // Получение текущей метки времени Unix с микросекундами
    static public function getMicroTimes()
    {
        $mt = explode(" ", microtime());
        return ((float)$mt[0] + (float)$mt[1]);
    }

    // Получение ID созданных свойств
    static public function getPropertyID($iblockID, $elementID)
    {
        $string = "PROPERTIES: ";
        $res = \CIBlockElement::GetProperty($iblockID, $elementID, array("sort" => "asc"), array("ACTIVE" => "Y"));
        while ($ob = $res->GetNext()) {
            $string = $string . $ob["CODE"] . ":" . $ob["ID"] . ". ";
        }
        return $string;
    }

    // Проверка существования инфоблока
    static public function existIB($iblockID)
    {
        // https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblock/getlist.php
        $res = \CIBlock::GetList(
            Array(),
            Array(
                "ID" => $iblockID,
                "CHECK_PERMISSIONS" => "N",
            ), true
        );

        $answer = $res->Fetch() ? true : false;
        return $answer;
    }

    // Проверка существования инфоблоков - Значения через запятую
    static public function getExistIBs($string) {
        $newArray = [];
        $array = explode(",", $string);

        foreach ($array as $ID) {
            if (!Helpers::existIB($ID)) {
                // Запись в Log
                $dt = date("Y-m-d H:i:s");
                $strLog = "[{$dt}] [WARNING] Инфоблока с ID=$ID не существует!";
                Log::saveToFile($strLog);
            } else {
                $newArray[] = $ID;
            }
        }

        return $newArray;
    }
}