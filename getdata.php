
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



$GetData = $_GET['getdata'];

$obj_store_fetch = new GDS\Store('Beacon');
$obj_store_fetch2 = new GDS\Store('Sensor');


/* return all data as json to contextApp */
if($GetData ==1){
    $items = array();
    $arr =  $obj_store_fetch->fetchAll();
    foreach ($arr as $obj_model) {
        $items[] = $obj_model->getData();
        //echo $obj_model->getKeyId();
      // print_r(array_values($items));
        //echo $obj_model->getData();
        //echo json_encode($items,JSON_PRETTY_PRINT);


    }
    echo '{"myarray":'. json_encode($items,JSON_PRETTY_PRINT) . '}';
}
else if($GetData == 2){

    $items = array();
    $arr =  $obj_store_fetch2->fetchAll();
    foreach ($arr as $obj_model) {
        $items[] = $obj_model->getData();

        //print_r(array_values($items));



    }
    echo '{"myarray":'. json_encode($items,JSON_PRETTY_PRINT) . '}';
}

?>

