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
    );
}
