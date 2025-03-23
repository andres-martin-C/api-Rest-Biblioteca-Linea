<?php

namespace Controller;

use Exception;
use Model\User;

class UserController
{

    /**
     *  TODO: Método para saber que se debe realizar
     *
     * @param array $accion Arreglo que contiene lo enviado en la ruta
     * @param string $method Verbo http que se envió en la petición
     * @return void
     */
    public static function procesoRealizar(array $accion, string $method): void {
        // * Dependiendo aquí mandara a llamar a la función que corresponda
        switch ($method) {
            // * Si es un método get entonces entrara aquí
            case 'get':
                self::obtenerUsuarios($accion);
                # code...
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
            throw new Exception("Enviarme un valor", 1);
        }
        $resultado = [];
        // * Variable para saber si es un numero
        $expreRegular = '/\d/';
        // ? Pregunto si es un numero lo que esta en la posición 2
        if (preg_match($expreRegular, $parametros[2]) === 1) {
            // * Si en la posición 3 no me envía nada entonces retornara un solo usuario
            if (!isset($parametros[3])) {
                $resultado = User::get_one_User($parametros[2]);
            } else {
                // * traerá usuarios por una columna especificada
                $resultado = User::get_filtret_User($parametros[2], $parametros[3]);
            }
            // * Si manda 'page' entonces retornara los usuarios de esa pagina
        } else if ($parametros[2] === 'page') {
            // * Si no me enviaron nada entonces puedo llamar al metodo get_all_User
            $resultado = (!isset($parametros[3])) ? User::get_all_User() 
            // * De lo contrario si me lo enviaron pregunto si es un numero
            : (preg_match($expreRegular, $parametros[3]) === 1 ? User::get_all_User($parametros[2]) 
            // * Si no es un numero entonces retornara un error.
            : (function () {
                    throw new Exception("Error de ruta se necesita un número", 1);
                })());
        }
        return $resultado;
    }

    private static function crearUsuario() {}

    private static function actualizarUsuario() {}

    private static function eliminarUsuario() {}
}
