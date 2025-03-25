<?php

namespace Controller;

use Errors\Error;
use Exception;
use Model\User;

class UserController
{
    /**
     *  TODO: Método para saber que se debe realizar
     *
     * @param array $accion Arreglo que contiene lo enviado en la ruta
     * @param array  $method Verbo http que se envió en la petición
     * @return void
     */
    public static function procesoRealizar(array $accion, string $method): array {
        $resultado = [];
        // * Dependiendo aquí mandara a llamar a la función que corresponda
        switch ($method) {
            // * Si es un método get entonces entrara aquí
            case 'get':
                $resultado = self::obtenerUsuarios($accion);
                break;
            // * Si es un método post entonces entrara aquí
            case 'post':
                # code...
                break;
            // * Si es un método put entonces entrara aquí
            case 'put':
                # code
                break;
            // * Si es un método Delete entrara aquí.
            default:
                # code...
                break;
        }
        return $resultado;
    }

    /**
     * TODO: Método que retornara usuarios los cuales le pida
     *
     * @param array $parametros
     * @return array
     */
    private static function obtenerUsuarios(array $parametros): array
    {
        // * Si no me envía nada entonces mostrara error
        if (!isset($parametros[2])) {
            throw new Exception( Error::$tipoError['datosFaltantes']['mensaje'], Error::$tipoError['datosFaltantes']['code'] );
        }
        $resultadoModel = [];
        // * Variable para saber si es un numero
        $expreRegular = '/\d/';
        // ? Pregunto si es un numero lo que esta en la posición 2
        if (preg_match($expreRegular, $parametros[2]) === 1) {
            // * Si en la posición 3 no me envía nada entonces retornara un solo usuario
            if (!isset($parametros[3])) {
                $resultadoModel = User::get_one_User($parametros[2]);
            } else {
                // * traerá usuarios por una columna especificada
                $resultadoModel = User::get_filtret_User($parametros[2], $parametros[3]);
            }
            // * Si manda 'page' entonces retornara los usuarios de esa pagina
        } else if ($parametros[2] === 'page') {
            // * Si no me enviaron nada entonces puedo llamar al metodo get_all_User
            $resultadoModel = (!isset($parametros[3])) ? User::get_all_User() 
            // * De lo contrario si me lo enviaron pregunto si es un numero
            : (preg_match($expreRegular, $parametros[3]) === 1 ? User::get_all_User( (int) ($parametros[3]) ) 
            // * Si no es un numero entonces retornara un error.
            : (function () {
                    throw new Exception( Error::$tipoError['rutaNoExiste']['mensaje'], Error::$tipoError['rutaNoExiste']['code'] );
                })());
        }else{
            throw new Exception( Error::$tipoError['rutaNoExiste']['mensaje'], Error::$tipoError['rutaNoExiste']['code'] );
        }
        return [ 'mensaje' => $resultadoModel , 'status' => 200 ];
    }

    private static function crearUsuario() {}

    private static function actualizarUsuario() {}

    private static function eliminarUsuario() {}
}
