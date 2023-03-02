<?php
require_once("controller/classess/dbCon_ulogin.php");
$username = $_REQUEST['username'];
if(empty($username)){
$rows = array("status" => 'error', "message" => "Please enter login username");
}else{
$strch="select * from users where 1 and username=:username";
$relist = $db->fetchRow($strch,array(":username"=>$username));
if(!empty($relist['id'])){
$rows = array("status" => 'success', "message" => "Great! Redirecting to next step","username"=>$relist['username']);
}else{
$rows = array("status" => 'error', "message" => "Invalid username");
}
}
header("Content-type: text/json;charset=utf-8");
echo json_encode($rows);
?>