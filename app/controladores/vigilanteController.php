<?php
require_once __DIR__ . '/../modelos/Vigilante.php';

class VigilanteController
{
    
    public function listar(): array
    {
        $vigilante = new Vigilante();
        return $vigilante->listar();
    }

    
    public function registrar(int $usuarioId, string $codigo): int
    {
        $vigilante = new Vigilante();
        return $vigilante->registrar($usuarioId, $codigo);
    }
}