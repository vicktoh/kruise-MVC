<?php
///MVC CONSTANTS path constants should be added with trailling slashes


define("APP_PATH", "app/");
define("CONTROLLERS", APP_PATH."controllers/");///path to your controllers
define("MODELS", APP_PATH."models/");
define("VIEWS", APP_PATH."views/");
define("UPLOAD_PATH", "images/profilepics/");
define("HELPERS", APP_PATH."helpers/");
define("LIBRARIES", APP_PATH."library/");
define("APP_START", '2017-12-01');
/// You may define other constants of your own here
/// ***********************************DATABASE CONFIGURATIONS**********************************///
define("SQL_HOST", "localhost");
define("SQL_USER", "kunle");
define("SQL_PASS", "love4lovelace");
define("SQL_DB", "dataphyte");


define("DB_DRIVER", "mysqli"); /// other driver values include mysql, pdo, mongodb
define("ABS_PATH", "http://localhost/dataphyte_search/");
define("DOCUMENTS", "documents/" ); /// file root for for file upload functionalities
define("WEB_ROOT", "/public_html/amebo/"); /// absolute  system path to your on the server or local machine to your root folder mostimes the same folder as index.php file
define("ASSET_URL", ABS_PATH);
//define("RELEASE_PATH", ABS_PATH . "Raw/");
define("PASS_PHRASE", "love4lovelace");
define("DANGER_RESPONSE", "danger_response");
define("OKAY_RESPONSE", "okay_response");


///**************************************************************Core Classes you want readily available for you add them to the core array and make sure the files are available at * app/core/mycoreclasses.php****************************************//////
$core = array();
$core[] = "App";
$core[] = "Controller";
$core[] = "Model"; 
///add some more the same way $core[] = "Name_of_Core_class";

foreach ($core as $class) {
    $name = APP_PATH."core/" . $class . ".php";
    if (file_exists($name)) {
        require_once($name);
    } else {
        echo "The Core class " . $class . " could not be found at " .$name . ".php";
        die();

    }
}
?>