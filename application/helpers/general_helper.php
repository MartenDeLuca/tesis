<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('adaptarString'))
{
    function adaptarString($busqueda){
        $salida = "";
        $dc = '"';  
        $qAux = $busqueda;
        $q3 = '';
        while(!empty($qAux)){
            if(strpos($qAux,"''")){  
                $q3 = $q3 . substr($qAux, 0,  strpos($qAux,"''")+2);            
                
                $qAux = substr($qAux, strpos($qAux,"''")+2);
            
            }else{
                if(strpos($qAux,"'")){   
                    $q3 = $q3 . substr($qAux, 0,  strpos($qAux,"'"));
                    $q3 = $q3 . "''"; 
                    $qAux = substr($qAux, strpos($qAux,"'")+1);             
                }else if(substr($qAux, 0,1)=="'"){       
                    $q3 = $q3 . substr($qAux, 0,  strpos($qAux,"'"));
                    $q3 = $q3 . "''"; 
                    $qAux = substr($qAux, strpos($qAux,"'")+1);                         
                }else {
                    $q3 = $q3 . $qAux;
                    $qAux = '';
                }
            }
        }
        $busqueda = $q3;
        return $busqueda;
    }   
}

if ( ! function_exists('EsVocal'))
{
    function EsVocal($letra) {
        $vocales='aeiouáéíóú';
        return (strpos($vocales,mb_strtolower($letra,'UTF-8'))!==false);
    }
}

if ( ! function_exists('proxima_letra'))
{
    function proxima_letra($letter){
        for($x = $letter; $x < 'ZZZ'; $x++){
            $x++;
            $next = $x;
            break;
        }
        return $next;
    }
}

if ( ! function_exists('cuatrimestre'))
{
    function cuatrimestre($fecha) {
        $mes = mes($fecha);
        $cuatrimestre = array("1" => "Enero, Febrero, Marzo", "2" => "Abril, Mayo, Junio", "3" => "Julio, Agosto, Septiembre", "4" => "Octubre, Noviembre, Diciembre");
        foreach ($cuatrimestre as $key => $value) {
            if(strpos($value, $mes) !== false){
                return array("Numero" => $key, "Valores" => $value);
            }
        }
        return array("Numero" => "", "Valores" => "");
    }
}

if ( ! function_exists('mes'))
{
    function mes($fecha){
        $date = strtotime($fecha);
        $mes = date('m', $date);
        $meses = array(
            "1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", 
            "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"
        );
        return $meses[(int)$mes]; 
    }
}

if ( ! function_exists('detectarNavegador')){
    function detectarNavegador()
    {
        $info = array();
        $browser=array("CHROME","SAFARI","MOZILLA","IE","OPR","NETSCAPE","FIREFOX", "EDGE");
        //$os=array("WIN","MAC","LINUX");
     
        # definimos unos valores por defecto para el navegador y el sistema operativo
        $info['browser'] = "OTHER";
        //$info['os'] = "OTHER";
     
        # buscamos el navegador con su sistema operativo
        foreach($browser as $parent)
        {
            $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);      
            //$f = $s + strlen($parent);
            //$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
            //$version = preg_replace('/[^0-9,.]/','',$version);
            if ($s){
                $info['browser'] = $parent;
                break;
                //$info['version'] = $version;
            }
        }
     
        # obtenemos el sistema operativo
        // foreach($os as $val)
        // {
            // if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
                // $info['os'] = $val;
        // }
     
        # devolvemos el array de valores
        return $info;
    }
}

if ( ! function_exists('base_url_sin_https'))
{
    function base_url_sin_https()
    {
        $base_url = base_url();
        return str_replace('https', 'http',$base_url); 
    }
}

if ( ! function_exists('get_real_ip'))
{
    function getIpPublica(){
        if (isset($_SERVER["HTTP_CLIENT_IP"])){
            return $_SERVER["HTTP_CLIENT_IP"];
        }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])){
            return $_SERVER["HTTP_FORWARDED"];
        } else{
            return $_SERVER["REMOTE_ADDR"];
        }
    }
}

if ( ! function_exists('llenarTreeview'))
{
    function llenarTreeview($treeview, $esInicio = '0'){
        $html = "";
        foreach ($treeview as $fila) {
            $carpeta = $fila["name"];
            $id = $fila["id"];
            $clase_treeview = '';
            $boton_treeview = '';
            $hijos_treeview = '';
            if($esInicio == '1'){
                $icono_treeview = 'fa-circle-o';    
            }else{
                $icono_treeview = 'fa-home';
            }
            
            if(isset($fila["nodes"])){                
                $hijos = $fila["nodes"];
                $clase_treeview = 'class="treeview"';
                $boton_treeview =
                '<span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>';
                $hijos_treeview = '<ul class="treeview-menu">';
                $hijos_treeview .= llenarTreeview($hijos, '1');
                $hijos_treeview .= '</ul>';
            }
            $html .= 
            '<li '.$clase_treeview.'>
                <a href="#" class="a_treeview" data-id="'.$id.'">
                    <i class="fa '.$icono_treeview.' i_treeview"></i> 
                    <span class="span_treeview">'.$carpeta.'</span>'.$boton_treeview.
                '</a>'
                .$hijos_treeview.
            '</li>'; 
        }
        return $html;
    }   
}


if(!function_exists('encrypt_url')){
    function encrypt_url($string, $Key = 'neestor1') {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($Key),
        $string, MCRYPT_MODE_CBC, md5(md5($Key))));
    }
}
if(!function_exists('decrypt_url')){
    function decrypt_url($string, $Key = 'neestor1') {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($Key), 
        base64_decode($string), MCRYPT_MODE_CBC, md5(md5($Key))), "\0");
    }
}