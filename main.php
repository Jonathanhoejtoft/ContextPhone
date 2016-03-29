
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

//$id = "key" . 3;
$id = "key" . mt_rand(100000,999999);  // id for both entities
$sensorType = $_GET['sensortype']; // Type
$value = $_GET['value']; // value for both
$timestamp = $_GET['timestamp'];
$timestampInject = date("Y-m-d H:i:s");
//$androidID = $_GET['androidID']; // value for both
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
$updateID = $_GET['EntityID'];
$GetData = $_GET['getdata'];

$obj_store_fetch = new GDS\Store('Beacon');
$obj_store_fetch2 = new GDS\Store('Sensor');


if($entityType == 1) {
    $entity = "Beacon";

    //$query = $obj_store_fetch->fetchAll();
    $query = $obj_store_fetch->fetchAll("select * from Beacon where UUID = '".$UUID."' AND major = '".$major."' AND minor= '".$minor."'");
    echo PHP_EOL, "Query found ", count($query), ":Beacon records", PHP_EOL . "<br>";

    if(!$query){
    echo "worked";
    echo "entityType: " . $entity;

    echo '<p/>ID: ', $id, "<br/>";

    $obj_1 = new GDS\Entity();
    $obj_1->Bid = $id;
    $obj_1->lat = $lat;
    $obj_1->long = $long;
    $obj_1->beaconID = $beaconID;
    $obj_1->major = $major;
    $obj_1->minor = $minor;
    //$obj_1->androidID = $androidID;
    $obj_1->UUID = $UUID;
    // Write it to Datastore
    $obj_store = new GDS\Store($entity);
    $obj_store->upsert($obj_1);

    $obj_store_fetch = new GDS\Store($entity);
    }
    else{
        echo "already exists";
    }
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
    $obj_2->Sid = $id;
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
        echo "   UUID: {$obj_model->UUID}, major: {$obj_model->major},minor: {$obj_model->minor},beaconID: {$obj_model->beaconID},BID: {$obj_model->Bid}", PHP_EOL . "<br>";
    }
}

function show2($arr)
{

    echo PHP_EOL, "Query found ", count($arr), ":sensor records", PHP_EOL . "<br>";
    foreach ($arr as $obj_model) {

        echo "  sensorType: {$obj_model->sensortype},Timestamp: {$obj_model->timestamp},SID: {$obj_model->Sid},value: {$obj_model->value}", PHP_EOL . "<br>";


    }
}

//show($obj_store_fetch->fetchAll("select * from Beacon where Bid=key387673"));
    show($obj_store_fetch->fetchAll());
    show2($obj_store_fetch2->fetchAll());
//show2($obj_store_fetch2->fetchAll("select * from Sensor where value=1"));



/* delete script */
if($deleteID == 'beacon'){
    //$arr_1 = $obj_store_fetch->fetchAll();
    $arr_1 = $obj_store_fetch->fetchOne("select * from Beacon where Bid='".$updateID."'"); // deleting based on GQL
    echo "Found ", count($arr_1), " records", PHP_EOL;
    if($arr_1 != 0){
        $obj_store_fetch->delete($arr_1);
        echo "deleted beacon entity with id:" .$updateID ;
    }
    else{
        echo "no id found";
    }
}
else if($deleteID == 'sensor'){

    //$arr_1 = $obj_store_fetch2->fetchById("4967730973245440");
    //$arr_1 = $obj_store_fetch2->fetchAll();
    $arr_1 = $obj_store_fetch2->fetchOne("select * from Sensor where Sid='".$updateID."'"); // deleting based on GQL
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
/*if($updateID){
    $arr_1 = $obj_store_fetch2->fetchOne("select * from Sensor where value='".$updateID."'"); // selecting based on GQL
    echo "Found ", count($arr_1), " records", PHP_EOL;
}
else{
    echo "no entity with that id found";
}*/
/* return all data as json to contextApp */
if($GetData ==1){
    $items = array();
   $arr =  $obj_store_fetch->fetchAll();
    foreach ($arr as $obj_model) {
        $items[] = $obj_model->getData();

        //print_r(array_values($items));
        echo json_encode($items, JSON_PRETTY_PRINT);


    }
}
else if($GetData == 2){

    $items = array();
    $arr =  $obj_store_fetch2->fetchAll();
    foreach ($arr as $obj_model) {
        $items[] = $obj_model->getData();

        //print_r(array_values($items));
        echo json_encode($items, JSON_PRETTY_PRINT);


    }
}

?>
<html>
<head>
    <link rel="stylesheet" href="/stylesheets/style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>
<body>
<hr>
<button><a id="delbeacon" href="\?del=beacon">delete beacon</a></button><br>
<button><a id="delsensor" href="\?del=sensor">delete sensor</a></button><br>
<button><a id="update" href="">update sensor</a></button><br>



<button><a id="addbeacon" class="clearurl" href="\?entype=1&id=1&lat=x234&long=y723&minor=m47&major=m67&uuid=uniqid1">add a beacon entity</a></button><br>
<button>
<?php
echo '<a id="addsensor" class="clearurl" href="\?entype=2&id=1&sensortype=sensor1&timestamp='.$timestampInject.'&androidID=ad1">add a sensor entity</a><br>';
?>
    </button>
<br>
<label for="val">write value to update/delete. EntityID:</label>
<input type="text" id="val">
<span id="chosenID">ID:</span>

<script>
    jQuery(document).ready(function($){
        /* clear url on refresh */
        //refreshPage();

        var val =  $("#val").val();
        $("#val").change(function(){
            $("#update").attr("href", "\?EntityID="+$("#val").val());
            $("#delbeacon").attr("href", "\?EntityID="+$("#val").val()+"&del=beacon");
            $("#delsensor").attr("href", "\?EntityID="+$("#val").val()+"&del=sensor");
            var hrefs = $('#addsensor').attr('href');
            var hrefb = $('#addbeacon').attr('href');
            $("#addsensor").attr("href", hrefs + "&value=" + $("#val").val());
            $("#addbeacon").attr("href", hrefb + "&beacon=" + $("#val").val());
            //&beacon=bID1
            $("chosenID").html("ID:" + $("#val").val());
            //alert("changed" + $("#val").val());
        });

        function refreshPage(){
            var url = window.location.href;
            url = url.split( '?' );
            if(url[1] == null){
                //alert("url is clear");
            }
            else{
                //alert("url contains params");
                window.location.href = url[0];
            }
        }
    });
</script>
<form action="/getdata.php" method="GET">

    <input type="submit">
</form>
</body>
</html>
<!--
 https://www.hurl.it/#top - to test an http request;
 http://contextphone-1253.appspot.com/
 -->