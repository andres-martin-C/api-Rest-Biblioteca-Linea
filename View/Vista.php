<?php

namespace View;

use BcMath\Number;

class Vista {

    public $estado = 0;
    public $mensaje = '';
    public $formatoDeVolucion = 'json';

    public function __construct( string $formatoDeVolucion = 'json' , int $estado = 500, string $mensaje = 'Error sistema') {
        $this->formatoDeVolucion = $formatoDeVolucion;
        $this->estado = $estado;
        $this->mensaje = $mensaje;
    }

    public function enviarMensaje(): void {
        http_response_code($this->estado);
        header('Content-Type: application/json; charset=utf8');
        if ($this->formatoDeVolucion === 'json') {
            echo json_encode(['Mensaje' => $this->mensaje , 'Status' => $this->estado], JSON_PRETTY_PRINT);
        } else {
            # code...
        }
        exit;
    }

    private static function formatearXml() {
        
    }

}
