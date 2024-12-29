<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Model\User;


// TODO: Capturar en autorization de los HEADER
// $headers = apache_request_headers();
// $autorization = $headers["Authorization"];
// $token = explode(" ", $autorization)[1];
// print_r($token);

// PARTE PARA CREAR EL TOKEN.
// $now = strtotime("now"); // Fecha actual.
// // // NOTA: No se debe mostrar aqui la llave, si no variables locales.
//  $key = 'prueba'; // Aqui sera la llave con la cual se crearan los tokens.
// // $payload = [
// //     'exp' => $now + 3600, // Le estoy diciendo que espire en una hora
// //     'data' => '1' // Aqui podria ser el id.
// // ];

// // $jwt = JWT::encode($payload, $key, 'HS256');
// // print_r($jwt);

// // PARTE PARA DECODEAR EL TOKEN
// try {
//     $decoded = JWT::decode($token, new Key($key, 'HS256'));
// } catch (\Throwable $th) {
//     echo("Token no valido");
// }

// print_r($decoded);

// VALIDAR EL TOKEN






// Importamos la clase connection

require_once 'Model/User.php';
// echo password_hash("1234",PASSWORD_BCRYPT);
// echo '<br>';
// echo password_hash("1234",PASSWORD_BCRYPT);
// // Obtener la instancia de la conexión
try {
    $model = User::updateUser(['','Andres','lol','game','andres@gamil.com','1234','user']);
    
} catch (PDOException $th) {
    echo "Error en la conexión o consulta: " . $th->getMessage();
}





// var_dump($_SERVER['REQUEST_URI']) ;
// echo $_SERVER['REQUEST_METHOD'];
