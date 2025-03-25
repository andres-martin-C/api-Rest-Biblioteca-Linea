<?php

namespace Errors;

class Error
{

    /**
     *  TODO: Tipos de errores 
     */
    public static $tipoError = array(
        'errorNoFormato' => array(
            'mensaje' => 'Se requiere la cabecera format para saber que formato retornar, los permitidos son json y xml',
            'code' => 400,
        ),
        'rutaNoExiste' => array(
            'mensaje' => 'Ruta no existe',
            'code' => 404,
        ),
        'controllerNoExiste' => array(
            'mensaje' => 'Controller no encontrado',
            'code' => 404,
        ),
        'metodoNoPermitodo' => array(
            'mensaje' => 'Método http no permitido',
            'code' => 405,
        ),
        'noConeccionBD'=> array(
            'mensaje' => 'No se pudo conectar a la base de datos',
            'code' => 500,
        ),
        'errorSintaxis' => array(
            'mensaje' => 'Error de sintaxis SQL	',
            'code' => 400,
        ),
        'correoNoExiste' => array (
            'mensaje' => 'Datos no validos',
            'code' => 404,
        ),
        'passwordYCorreoFaltan' => array (
            'mensaje' => 'Se requieren el correo y la contraseña',
            'code' => 404,
        ),
        'correoExistente' => array (
            'mensaje' => 'Correo ya existe',
            'code' => 404,
        ),
        'datosFaltantes' => array (
            'mensaje' => 'Datos faltantes',
            'code' => 404,
        ),

    );
}
