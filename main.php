
<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function google_api_php_client_autoload($className) {
    $classPath = explode('_', $className);
    if ($classPath[0] != 'Google') {
        return;
    }
    // Drop 'Google', and maximum class file path depth in this project is 3.
    $classPath = array_slice($classPath, 1, 2);
    $filePath = dirname(__FILE__) . '/' . implode('/', $classPath) . '.php';
    if (file_exists($filePath)) {
        require_once($filePath);
    }
}
spl_autoload_register('google_api_php_client_autoload');

require_once("GDS/Entity.php");
require_once("GDS/Gateway.php");
require_once("GDS/Mapper.php");
require_once("GDS/Schema.php");
require_once("GDS/Store.php");

require_once("GDS/Gateway/ProtoBuf.php");
require_once("GDS/Mapper/ProtoBuf.php");

/*//define GDS constants
define('GDS_APP_NAME', 'contextphone-1253'); //Name of the app
define('GDS_SERVICE_ACCOUNT_NAME', 'jonathan@contextphone-1253.iam.gserviceaccount.com'); //Google service account
define('GDS_DATASET_ID', 's~contextphone-1253'); //Name of the app prepended by "s~"*/


// Build a new entity
$obj_book = new GDS\Entity();
$obj_book->title = 'hans og grete';
$obj_book->author = 'William Shakespeare';
$obj_book->isbn = '18402243394';

// Write it to Datastore
$obj_store = new GDS\Store('test');
$obj_store->upsert($obj_book);
if($obj_store){
    echo "New entity added!" . "<br>";
    echo "title: " . $obj_book->title . "<br>";
    echo "author: " . $obj_book->author . "<br>";
    echo "isbn: " . $obj_book->isbn . "<br>";
}
else {
    echo "failed";
}


?>
<html>
<head>
    <link rel="stylesheet" href="/stylesheets/style.css" />
</head>
<body>
<?php

?>

</body>
</html>