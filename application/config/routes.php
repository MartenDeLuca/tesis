<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'usuario';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['configuracion'] = 'Configuracion';
$route['tablero'] = 'Tablero';
$route['reglas'] = 'Regla/reglas';
$route['agregar-regla'] = 'Regla/agregar_regla';
$route['modificar-regla'] = 'Regla/modificar_regla';
$route['detalle-regla'] = 'Regla/detalle_regla';
$route['detalle-correo'] = 'Regla/detalle_correo';


$route['alertas'] = 'Regla/alertas';

$route['login'] = 'usuario';
$route['licencias'] = 'usuario/licencias';
$route['agregar-licencia'] = 'usuario/agregar_licencia';
$route['confirmar'] = "usuario/confirmar";
$route['restablecer'] = "usuario/restablecer";
$route['registro'] = 'usuario/registro';