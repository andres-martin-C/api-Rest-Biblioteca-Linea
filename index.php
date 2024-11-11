<?php
// Importamos la clase connection

require_once './Database/connection.php';
// Obtener la instancia de la conexión
$conexion = Connection::instanceObject();
// Acceder al objeto PDO
$pdo = $conexion->objPDO;





// var_dump($_SERVER['REQUEST_URI']) ;
// echo $_SERVER['REQUEST_METHOD'];
