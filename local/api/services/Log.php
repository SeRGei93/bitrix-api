<?php

namespace app\services;

class Log
{
    static public function saveToFile($string)
    {
        $fd = fopen(LOG_FILENAME, "a+") or Render::message("Не удалось создать log-файл!", "danger");
        fwrite($fd, $string . PHP_EOL);
        fclose($fd);
    }

    static public function prepareString($iblockID, $elementID, $count, $current, $properties, $timeStart)
    {
        $dt = date("Y-m-d H:i:s");
        $remain = $count - $current;
        if (API_OPERATION == "add") $properties = Helpers::getPropertyID($iblockID, $elementID);
        $strLog = "[{$dt}] [" . strtoupper(API_OPERATION) . "] IBLOCK_ID: {$iblockID}. ELEMENT_ID: {$elementID}. {$properties}Осталось:{$remain}.";

        // Время выполнения
        $timeEnd = Helpers::getMicroTimes();
        $testTotal = round($timeEnd - $timeStart, 4);
        $strLog = $strLog . " Время выполнения итерации:{$testTotal} сек.";
        return $strLog;
    }

}