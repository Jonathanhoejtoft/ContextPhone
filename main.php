
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

/*//define GDS constants
define('GDS_APP_NAME', 'contextphone-1253'); //Name of the app
define('GDS_SERVICE_ACCOUNT_NAME', 'jonathan@contextphone-1253.iam.gserviceaccount.com'); //Google service account
define('GDS_DATASET_ID', 's~contextphone-1253'); //Name of the app prepended by "s~"*/

/* define the get request */
foreach($_GET as $key=>$value){
    echo $key, ' => ', $value, "<br/>";
}

// defining get parameters
$entityType = $_GET['entype']; // int - specifies entity type #1 = Beacon; #2 = Sensor
$entity = null; // var for defining sensor or beacon
// define sensor params
$id = $_GET['id']; // id for both entities
$sensorType = $_GET['sensortype']; // Type
$value = $_GET['value']; // value for both
$timestamp = $_GET['timestamp'];
$timestampInject = date("Y-m-d H:i:s");
$androidID = $_GET['androidID']; // value for both
// define beacon params
$lat = $_GET['lat'];
$long = $_GET['long'];
$beaconID = $_GET['beacon'];
$major = $_GET['major'];
$minor = $_GET['minor'];
$UUID = $_GET['uuid'];
//$sensor = $_GET['sensor'];
// only for testing
$deleteID = $_GET['del'];
$updateID = $_GET['upd'];

if($entityType == 1) {
    $entity = "Beacon";
    echo "entityType: " . $entity;

    echo '<p/>ID: ', $id, "<br/>";

    $obj_1 = new GDS\Entity();
    //$obj_1->id = $id;
    $obj_1->lat = $lat;
    $obj_1->long = $long;
    $obj_1->beaconID = $beaconID;
    $obj_1->major = $major;
    $obj_1->minor = $minor;
    $obj_1->androidID = $androidID;
    $obj_1->UUID = $UUID;
    // Write it to Datastore
    $obj_store = new GDS\Store($entity);
    $obj_store->upsert($obj_1);

    $obj_store_fetch = new GDS\Store($entity);
}
else if($entityType == 2){
    $entity = "Sensor";
    echo "entityType: " . $entity;
    //echo '<a class="clearurl" href="\?entype=2&id=1&sensortype=sensor1&value=723&timestamp='.$timestampInject.'&androidID=ad1">add a sensor entity</a><br>'
    $obj_2 = new GDS\Entity();
    //$obj_2->id = $id;
    $obj_2->sensortype = $sensorType;
    $obj_2->value = $value;
    $obj_2->timestamp = $timestamp;
    $obj_2->androidID = $androidID;
    // Write it to Datastore
    $obj_store = new GDS\Store($entity);
    $obj_store->upsert($obj_2);

    $obj_store_fetch = new GDS\Store($entity);
}

    if($obj_store){
        echo "New entity added!" . "<br>";
        //echo "Beacon_ID: " . $obj_book->id . "<br>";
        //echo "BeaconName: " . $obj_book->beaconname . "<br>";

    }



/* end get request */

// Build a new entity

/*$obj_book = new GDS\Entity();
$obj_book->title = 'grimme fortællinger';
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
}*/



$obj_store_fetch = new GDS\Store('Beacon');
$obj_store_fetch2 = new GDS\Store('Sensor');

//show($obj_store_fetch->fetchAll());

/**
 * Show result data
 *
 * @param $arr
 */
function show($arr)
{
    echo PHP_EOL, "Query found ", count($arr), ":Beacon records", PHP_EOL . "<br>";
    foreach ($arr as $obj_model) {
        echo "   ID: {$obj_model->ID}, beaconID: {$obj_model->beaconID}", PHP_EOL . "<br>";
    }
}

function show2($arr)
{

    echo PHP_EOL, "Query found ", count($arr), ":sensor records", PHP_EOL . "<br>";
    foreach ($arr as $obj_model) {

        echo "   ID: {$obj_model->ID}, value: {$obj_model->value}", PHP_EOL . "<br>";


    }
}

    show($obj_store_fetch->fetchAll());
    show2($obj_store_fetch2->fetchAll());



/* delete script */
if($deleteID == 1){
    $arr_1 = $obj_store_fetch->fetchAll();
    echo "Found ", count($arr_1), " records", PHP_EOL;
    $obj_store_fetch->delete($arr_1);
    echo "deleted all beacon entities!";
}
else if($deleteID == 2){

    //$arr_1 = $obj_store_fetch2->fetchById("4967730973245440");
    //$arr_1 = $obj_store_fetch2->fetchAll();
    $arr_1 = $obj_store_fetch2->fetchOne("select * from Sensor where value=725"); // deleting based on GQL
    echo "Found ", count($arr_1), " records", PHP_EOL;
    if($arr_1 != 0){
        $obj_store_fetch2->delete($arr_1);
        echo "deleted all sensor entities!";
    }
    else{
        echo "no id found";
    }


}
/* update */
if($updateID){
    $arr_1 = $obj_store_fetch2->fetchOne("select * from Sensor where value='".$updateID."'"); // selecting based on GQL
    echo "Found ", count($arr_1), " records", PHP_EOL;
}
else{
    echo "no entity with that id found";
}

?>
<html>
<head>
    <link rel="stylesheet" href="/stylesheets/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>
<hr>
<button><a href="\?del=1">delete beacon</a></button><br>
<button><a id="delsensor" href="\?del=2">delete sensor</a></button><br>
<button><a id="update" href="">update sensor</a></button><br>



<button><a class="clearurl" href="\?entype=1&id=1&lat=x234&long=y723&beacon=bID1&minor=m47&major=m67&androidID=ad1&uuid=uniqid1">add a beacon entity</a></button><br>
<button>
<?php
echo '<a class="clearurl" href="\?entype=2&id=1&sensortype=sensor1&timestamp='.$timestampInject.'&androidID=ad1&value=755">add a sensor entity</a><br>';
?>
    </button>
<br>
<label for="val">write value to update/delete</label>
<input type="text" id="val">

<script>
    jQuery(document).ready(function($){
        /* clear url on refresh */
        var url = window.location.href;
        url = url.split( '?' );
        if(url[1] == null){
            //alert("url is clear");
        }
        else{
            //alert("url contains params");
            window.location.href = url[0];
        }

        var val =  $("#val").val();
        $("#val").change(function(){
            $("#update").attr("href", "\?upd="+$("#val").val());
            $("#delsensor").attr("href", "\?upd="+$("#val").val()+"&del=2");
            alert("changed" + $("#val").val());
        });
    });
</script>
</body>
</html>
<!--
 https://www.hurl.it/#top - to test an http request;
 http://contextphone-1253.appspot.com/
 -->