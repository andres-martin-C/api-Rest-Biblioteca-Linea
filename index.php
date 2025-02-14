<?php
require 'vendor/autoload.php';


use Model\User;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// TODO: Capturar en autorization de los HEADER
// $headers = apache_request_headers();
// $autorization = $headers["Authorization"];
// $token = explode(" ", $autorization)[1];
// print_r($token);

// PARTE PARA CREAR EL TOKEN.

// // // NOTA: No se debe mostrar aqui la llave, si no variables locales.




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
    $input = file_get_contents('php://input');
    // Decodificar el contenido si es JSON
    $data = json_decode($input, true);  // 'true' para que sea un array asociativo
    print_r($data);
    $model = User::autenticacion($data);
    print_r($model);
} catch (PDOException $th) {
    echo "Error en la conexión o consulta: " . $th->getMessage();
}





// var_dump($_SERVER['REQUEST_URI']) ;
// echo $_SERVER['REQUEST_METHOD'];
