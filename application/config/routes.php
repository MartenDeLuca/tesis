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


$route['actividades'] = 'Regla/actividades';

$route['seguimiento'] = 'Seguimiento';
$route['agregar-actividad'] = 'Seguimiento/agregar_actividad';
$route['modificar-actividad'] = 'Seguimiento/modificar_actividad';

$route['plantillas'] = 'plantilla';
$route['agregar-plantilla'] = 'Plantilla/agregar_plantilla';
$route['modificar-plantilla'] = 'Plantilla/modificar_plantilla';


$route['login'] = 'usuario';
$route['licencias'] = 'usuario/licencias/0';
$route['usuarios'] = 'usuario/licencias/1';
$route['agregar-licencia'] = 'usuario/agregar_licencia';
$route['confirmar'] = "usuario/confirmar";
$route['restablecer'] = "usuario/restablecer";
$route['registro'] = 'usuario/registro';