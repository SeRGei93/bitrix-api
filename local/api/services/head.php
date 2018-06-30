<?php
require_once "./config/main.php";
require(ROOT_DIR . "/bitrix/modules/main/include/prolog_before.php");
require_once SERVICES_DIR . "Autoloader.php";
spl_autoload_register([new \app\services\Autoloader(), "loadClass"]);
require_once ROOT_DIR . "/vendor/autoload.php";
\CModule::IncludeModule("iblock");