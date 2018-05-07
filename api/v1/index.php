<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/funcoes.php';

$endpoint = getEndpoint();

if (null === $endpoint) {
    die(prepararRetorno(1, "Endpoint inválido."));
}

$request_method = strtolower($_SERVER['REQUEST_METHOD']);
$request_methods_accept = array("delete", "get", "post", "put");

if (!in_array($request_method, $request_methods_accept, true)) {
    die(prepararRetorno(1, "Tipo de requisição não implementado."));
}

require_once __DIR__ . "/endpoints/{$request_method}.php";

if (function_exists($endpoint)) {
    $retorno = $endpoint();
} else {
    $retorno = prepararRetorno(1, "Endpoint '{$endpoint}' não localizado.");
}

die($retorno);
