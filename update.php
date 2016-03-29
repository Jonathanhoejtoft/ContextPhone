
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
//session_start();
/* setting the default timezone to Copenhagen/denmark */
date_default_timezone_set('Europe/Copenhagen');



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
require_once 'GDS/Exception/GQL.php';
require_once("GDS/Gateway/ProtoBuf.php");
require_once("GDS/Mapper/ProtoBuf.php");
require_once("GDS/Mapper/ProtoBufGQLParser.php");



$value = $_GET['value'];
$keyID = $_GET['keyid'];

/*entitydata by get */
$sensorType = $_GET['sensortype'];
$timestamp = date("Y-m-d H:i:s");

$obj_store_fetch = new GDS\Store('Beacon');
$obj_store_fetch2 = new GDS\Store('Sensor');


if($value){

        //$arr_1 = $obj_store_fetch->fetchAll();
        $arr_1 = $obj_store_fetch->fetchOne("select * from Sensor where Sid='".$keyID."'"); // deleting based on GQL
        echo "Found ", count($arr_1), " records", PHP_EOL;
    /* deleting key, add with new data */
        if($arr_1 != 0){
            $obj_store_fetch->delete($arr_1);
            echo "deleted beacon entity with id:" .$keyID ;
            /*add new entity */

            $obj_2 = new GDS\Entity();
            $obj_2->sensortype = $sensorType;
            $obj_2->value = $value;
            $obj_2->timestamp = $timestamp;
            $obj_2->Sid = $keyID;

            // Write it to Datastore
            $obj_store = new GDS\Store("Sensor");
            $obj_store->upsert($obj_2);
        }
        else{
            echo "no id found";
        }

    echo "is set" . $value;
}
else{
    echo "not set" . $value;
}

?>

