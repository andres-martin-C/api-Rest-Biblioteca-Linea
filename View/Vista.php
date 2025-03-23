<?php

namespace View;

class Vista {

    public $estado = '';
    public $mensaje = '';

    public function __construct(string $estado = '500', string $mensaje = 'Error sistema') {
        $this->estado = $estado;
        $this->mensaje = $mensaje;
    }

    private function enviarMensaje(string $formatoDeVolucion = 'json'): void {
        if (true) {
            # code...
        } else {
            # code...
        }
        
    }

}
