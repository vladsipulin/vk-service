<?php
include_once("Controller/Api.php");
use Service\Controller\Api\Api;
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

Api::init();
$data = ($_SERVER['REQUEST_METHOD'] == 'GET') ? $_GET : $_POST;
$response = Api::handleRequest(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $data);

if($response) {
    echo json_encode($response);
}