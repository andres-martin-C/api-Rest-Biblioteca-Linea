<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use View\Vista;
use Errors\Error;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// * Formato por default
$formato = 'json';
// * Creacion de instancia para retornar respuesta HTTP
$respuestaHttp = new Vista();
// * Variable que contendrá los errores.
/**
 * TODO: Función que captura los errores.
 */
set_exception_handler(function ($expection) use ($respuestaHttp, $formato) {
    // * Asignacion de valores para el retorno
    $respuestaHttp->formatoDeVolucion = $formato;
    $respuestaHttp->mensaje = $expection->getMessage();
    $respuestaHttp->estado = ($expection->getCode() === 0) ? 400 : $expection->getCode();
    // * Retornar la respuesta
    $respuestaHttp->enviarMensaje();
});

$formatosPermitidos = ['json', 'xml'];
// * Me regresa las cabeceras de la petición pero sus keys las convierto en minúsculas
$headers = array_change_key_case(apache_request_headers());
// * Verifico si en el encabezado 'format' no está presente o si su valor no es 'json' o 'xml'.  
// * Si alguna de estas condiciones se cumple, marco un error.  
// * De lo contrario, retorno el valor del formato en minúsculas.
$formato = (!isset($headers['format']) || !in_array(strtolower($headers['format']), $formatosPermitidos)) ?
    (function () {
        throw new Exception(Error::$tipoError['errorNoFormato']['mensaje'], Error::$tipoError['errorNoFormato']['code']);
    })()
    : strtolower($headers['format']);

// * Rutas permitidas y a que controlador lo reenviara
$rutasPermitidas = [
    "user" => "Controller\\UserController",
    "libro" => "Controller\\LibroController",
    "carrito" => "Controller\\CarritoController",
    "compra" => "Controller\\CompraController",
    "librovendido" => "Controller\\LibroVendidoController",
    "authentication" => "Controller\\UserController",
    "register" => "Controller\\UserController",
];

$metodosHttpPermitidos = ['get', 'post', 'put', 'delete'];

// * Divide la ruta buscando el caracter '/' y se lo almaceno a la variable ( $url ).
$url = explode("/", $_SERVER['REQUEST_URI']);
$url = array_filter($url); // Elimina las posiciones vacías del arreglo
$url = array_values($url); // Reindexa el array o sea los vuelve a numerar desde la posicion 0

// * Si lo que piden el la petición no esta en rutas permitidas entonces tirar error.
if (!array_key_exists($url[1], $rutasPermitidas)) throw new Exception( Error::$tipoError['rutaNoExiste']['mensaje'], Error::$tipoError['rutaNoExiste']['code'] );

// * Aquí obtenemos la clase y la variable $ClassController se vuelve una clase
$ClassController = $rutasPermitidas[$url[1]];

// * Pregunto si se ha importado la clase si no tira error.
if (!class_exists($ClassController)) throw new Exception(Error::$tipoError['controllerNoExiste']['mensaje'], Error::$tipoError['controllerNoExiste']['code']);

// * Obtengo el metodo http y lo pongo en minúsculas
$methodHttp = strtolower($_SERVER['REQUEST_METHOD']);

// * Valido si esta permitido el método que me enviaron en mi arreglo.
if (!in_array($methodHttp, $metodosHttpPermitidos)) throw new Exception( Error::$tipoError['metodoNoPermitodo']['mensaje'], Error::$tipoError['metodoNoPermitodo']['code'] );

// ! Obtener el body json
// var_dump(file_get_contents('php://input'));


// TODO: Llamar a un metodo static de la clases para saber que hacer y no tener que crear instancias de la clase del controller.
$elValorRetorno = $ClassController::procesoRealizar($url, $methodHttp);
$respuestaHttp->estado = $elValorRetorno['status'];
$respuestaHttp->mensaje = $elValorRetorno['mensaje'];
$respuestaHttp->enviarMensaje();



