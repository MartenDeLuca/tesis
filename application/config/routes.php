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

$route['actividades'] = 'Regla/actividades';

$route['seguimiento'] = 'Seguimiento';
$route['agregar-actividades'] = 'Seguimiento/agregar_actividad';
$route['modificar-actividad'] = 'Seguimiento/modificar_actividad';
$route['detalle-actividad'] = 'Seguimiento/modificar_actividad';

$route['agregar-mails'] = 'Seguimiento/agregar_mail';
$route['detalle-correo'] = 'Seguimiento/detalle_correo';
$route['detalle-mail'] = 'Seguimiento/detalle_correo';


$route['plantillas'] = 'plantilla';
$route['agregar-plantilla'] = 'Plantilla/agregar_plantilla';
$route['modificar-plantilla'] = 'Plantilla/modificar_plantilla';

$route['clientes'] = 'seguimiento/clientes';
$route['detalle-cliente'] = 'seguimiento/detalle_cliente';
$route['vista_cliente'] = 'seguimiento/vista_cliente';

$route['detalle-comprobante'] = 'cobranza/detalles';

$route['login'] = 'usuario';
$route['licencias'] = 'usuario/licencias/0';
$route['usuarios'] = 'usuario/licencias/1';
$route['agregar-licencia'] = 'usuario/agregar_licencia';
$route['confirmar'] = "usuario/confirmar";
$route['restablecer'] = "usuario/restablecer";
$route['registro'] = 'usuario/registro';