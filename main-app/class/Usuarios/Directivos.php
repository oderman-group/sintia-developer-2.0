<?php

class Directivos {

    protected $tipo          = 'DIRECTIVO';
    protected $nombre        = null;
    protected $nombreUsuario = null;
    protected $clave         = null;
    protected $email         = null;

    protected $usuariosPadre;

    public function __construct(UsuariosPadre $usuariosPadre) {
        $this->usuariosPadre = $usuariosPadre;
    }

    public function guardarDirectivo() {
        
    }
}