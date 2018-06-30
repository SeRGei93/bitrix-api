<?php
// Константы
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("STOP_STATISTICS", true); // Отключение автоматического сбора статистики
define("NO_AGENT_CHECK", true); // Отключает выполнение всех агентов

define("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
define("APP_DIR", ROOT_DIR . "/local/api/");

define("ASSETS_DIR", APP_DIR . "assets/");
define("CONFIG_DIR", APP_DIR . "config/");
define("LOGS_DIR", APP_DIR . "logs/");
define("SERVICES_DIR", APP_DIR . "services/");
define("VIEWS_DIR", APP_DIR . "views/");

define("PARAMS_FILENAME", CONFIG_DIR . "params.php");
define("LOG_FILENAME", LOGS_DIR . date("Y-m-d") . "_api.log");

define("ROOT_HOST", $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"]);

define("API_STEP", 6000);