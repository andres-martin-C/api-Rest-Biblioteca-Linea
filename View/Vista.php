<?php

namespace View;

class Vista {

    public $estado = '';
    public $mensaje = '';

    public function __construct(string $estado = '500', string $mensaje = 'Error sistema') {
        $this->estado = $estado;
        $this->mensaje = $mensaje;
    }

    public function enviarMensaje(string $formatoDeVolucion = 'json', array $mensaje = [] ): void {
        http_response_code($mensaje['estado']);
        header('Content-Type: application/json; charset=utf8');
        if ($formatoDeVolucion === 'json') {
            echo json_encode($mensaje, JSON_PRETTY_PRINT);
        } else {
            # code...
        }
        exit;
    }

    private static function formatearXml() {
        
    }

}
