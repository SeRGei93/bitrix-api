<?php

namespace app\services;

class Ajax
{
    // Загрузка в Битрикс
    public static function bitrix()
    {

        // Проверка существования ИБ
        if (!Helpers::existIB(Api::$gets['IBLOCK'])) {
            $strLog = "Указанный инфоблок № " . Api::$gets['IBLOCK'] . " не существует!";
            Log::saveToFile($strLog);
            exit;
        }

        $timeStart = Helpers::getMicroTimes();

        $result["status"] = "success";
        $iteration = 1;

        $el = new \CIBlockElement;

        while ((Api::$gets['current'] <= Api::$gets['COUNT']) AND ($iteration <= Api::$gets['STEP'])) {

            if (API_OPERATION == "delete") {
                $arSelect = Array("ID", "IBLOCK_ID");
                $arFilter = Array("IBLOCK_ID" => Api::$gets['IBLOCK'], "CHECK_PERMISSIONS" => "N");

                // Удаление 1 конкретного элемента
                if (Api::$gets['COUNT'] == 1 AND Api::$gets['element_id'] != 0) {
                    $arFilter = Array("ID" => Api::$gets['element_id']);
                }

                // Использование nTopCount гораздо эффективнее т.к. он не делает дополнительный запрос на получение записей выборки
                $res = $el->GetList(Array(), $arFilter, false, Array("nTopCount" => Api::$gets['STEP']), $arSelect);

                $step = 0;
                // Установка параметра false позволяет ограничить выборку только преобразованными значениями полей.
                while ($ob = $res->GetNext(true, false)) {
                    $elementID = $ob['ID'];

                    // Для ускорения процесса, считываем свойства только на первой итерации, т.к. ID у всех одинаковый
                    if ($step == 0) {
                        $properties = Helpers::getPropertyID($ob['IBLOCK_ID'], $elementID);
                    }

                    if (!($el->Delete($elementID))) {
                        $result["status"] = "error";
                        $result["message"] = $el->LAST_ERROR;
                        break;
                    }

                    $step++;
                }

                $step--;
                Api::$gets['current'] += $step;
                $iteration += $step;
                if ($result["status"] == "error") {
                    break;
                }
            }

            if (API_OPERATION == "add") {

                $defaultName = "Тест материал #" . Api::$gets['current'];

                // Добавляем данные в элемент
                $arFields = Array(
                    "IBLOCK_ID" => Api::$gets['IBLOCK'], // ID информационного блока
                    "NAME" => $defaultName, // Название элемента в ИБ - Поле "NAME"
                    "ACTIVE" => "Y"
                );

                // Добавляем данные во множественное свойство
                if (Api::$gets['PROPERTY'] == "Y") {
                    $townProperty = "Город #" . Api::$gets['current'];
                    $countryProperty = "Страна #" . Api::$gets['current'];
                    $regionProperty = "Регион #" . Api::$gets['current'];

                    $arFields ["PROPERTY_VALUES"] = array(
                        "TOWN" => [$townProperty, $countryProperty, $regionProperty]
                    );
                }

                // Отключаем индексацию элемента для поиска для повышения производительности - 3 параметр
                // https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/add.php
                if (!($elementID = $el->Add($arFields, false, false))) {
                    $result["status"] = "error";
                    $result["message"] = $el->LAST_ERROR;
                    break;
                }

            }

            Api::$gets['current']++;
            $iteration++;
        }

        Api::$gets['current']--;
        $result["current"] = Api::$gets['current'];

        // Лог
        if ($result["status"] == "success") {
            $strLog = Log::prepareString(Api::$gets['IBLOCK'], $elementID, Api::$gets['COUNT'], Api::$gets['current'], $properties, $timeStart);
            $result["message"] = $strLog;
            Log::saveToFile($strLog);
        }

        // Вывод JSON
        self::jsonOutput($result);
        exit;
    }

    // Вывод ответа для Ajax в формате JSON
    public static function jsonOutput($result)
    {
        // Сброс буфера в Битрикс перед выводом Ajax результата
        $GLOBALS["APPLICATION"]->RestartBuffer();
        echo json_encode($result);
    }
}