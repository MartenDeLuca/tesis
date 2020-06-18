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

if(!function_exists('armado_where')){
    function armado_where($columna, $tipo_busqueda, $busqueda, $tipo){
        $where = "";
        $buscar3 = adaptarString($busqueda);
        
        if($tipo == "string"){
            if($tipo_busqueda == "1"){
                $where = $where." ifnull(".trim($columna).",'') like '%".$buscar3."%'";   
            }else if($tipo_busqueda == "2"){
                $where = $where." ifnull(".trim($columna).",'') not like '%".$buscar3."%'";
            }else if($tipo_busqueda == "3"){
                $where = $where." ifnull(".trim($columna).",'') = '".$buscar3."'";  
            }else if($tipo_busqueda == "4"){
                $where = $where." ifnull(".trim($columna).",'') <> '".$buscar3."'"; 
            }else if($tipo_busqueda == "5"){
                $where = $where." ifnull(".trim($columna).",'') like '".$buscar3."%'";  
            }else if($tipo_busqueda == "6"){
                $where = $where." ifnull(".trim($columna).",'') like '%".$buscar3."'";
            }else if($tipo_busqueda == "7"){                        
                $where = $where." ifnull(".trim($columna).", '') = ''"; 
            }else if($tipo_busqueda == "8"){
                $where = $where." ifnull(".trim($columna).", '') <> ''";
            }
        }
        if($tipo == "float" || $tipo == "int"){
            if($tipo_busqueda == "11"){
                $where = $where.trim($columna)." >= '".$buscar3."'";
            }else if($tipo_busqueda == "12"){
                $where = $where.trim($columna)." <= '".$buscar3."'";
            }else if($tipo_busqueda == "15"){
                $where =  $where.trim($columna)." > '".$buscar3."'";    
            }else if($tipo_busqueda == "16"){
                $where =  $where.trim($columna)." < '".$buscar3."'";
            }else if($tipo_busqueda == "17"){
                $where = $where." ifnull(".trim($columna).",'0') = '".$buscar3."'"; 
            }else if($tipo_busqueda == "18"){
                $where = $where." ifnull(".trim($columna).",'0') <> '".$buscar3."'";
            }else if($tipo_busqueda == "19"){
                $where = $where." ifnull(".trim($columna).",'0') like '%".$buscar3."%'";
            }else if($tipo_busqueda == "20"){
                $where = $where." ifnull(".trim($columna).",'0') not like '%".$buscar3."%'";
            }else if($tipo_busqueda == "21"){
                $where = $where." ifnull(".trim($columna).",'') like '".$buscar3."%'";  
            }else if($tipo_busqueda == "22"){
                $where = $where." ifnull(".trim($columna).",'') like '%".$buscar3."'";
            }
        }

        if($tipo == "date"){
            if($tipo_busqueda == "10"){
                $where = $where." datediff(dd,".trim($columna).",now()) <= ".$buscar3." and datediff(dd,".trim($columna).",now()) >= 0";
            }else if($tipo_busqueda == "11"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where = $where.trim($columna)." >= '".$buscar3."'";
            }else if($tipo_busqueda == "12"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where = $where.trim($columna)." <= '".$buscar3."'";
            }else if($tipo_busqueda == "15"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where =  $where.trim($columna)." > '".$buscar3."'";    
            }else if($tipo_busqueda == "16"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where =  $where.trim($columna)." < '".$buscar3."'";
            }else if($tipo_busqueda == "13"){
                $buscar_desde = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $buscar_hasta = substr($buscar3,19,4)."-".substr($buscar3,16,2)."-".substr($buscar3,13,2);
                $where = $where.trim($columna)." >= '".$buscar_desde."' and ".trim($columna)." <= '".$buscar_hasta."'";
            }else if($tipo_busqueda == "14"){
                $year = substr($buscar3,3,4);
                $month = substr($buscar3,0,2);
                $where = $where." year(".trim($columna).") = '".$year."' and month(".trim($columna).") = '".$month."'";
            }else if($tipo_busqueda == "19"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where = $where." ".trim($columna)." = '".$buscar3."'"; 
            }else if($tipo_busqueda == "20"){
                $buscar3 = substr($buscar3,6,4)."-".substr($buscar3,3,2)."-".substr($buscar3,0,2);
                $where = $where." ".trim($columna)." <> '".$buscar3."'";
            }
        }
        return $where;
    }   
}

if(!function_exists('procesarWhere')){
    function procesarWhere($columna, $busqueda, $tipo_busqueda, $opciones_float, $opciones_date, $opciones_string, $where, $array_columna, $sql_columna, $tipo_columna){
        $posicion = 1;
        $busquedasGuardadas = "";
        if(!empty($columna)){
            $columna = explode("***", $columna);
            $tipo_busqueda = explode("***", $tipo_busqueda);
            $busqueda = explode("***", $busqueda);
            $tamano = count($columna);
            $where_busqueda = "";

            if($tamano > 0){
                if(!empty($columna[0])){
                    $comienzo = " ";
                    for($i = 0; $i < $tamano; $i++){
                        $valor = $columna[$i];
                        $clase_input = "";  
                        $row_busquedasGuardadas = "";
                        $pos = array_search($valor, $sql_columna);
                        log_message("error", $pos." ".$valor);
                        $valor = $array_columna[$pos];
                        $columna_sql = $sql_columna[$pos];
                        $tipo = $tipo_columna[$pos];
                        
                        if($tipo == "string"){
                            $opciones = $opciones_string;
                        }else if($tipo == "date"){
                            $opciones = $opciones_date;
                            $clase_input = "date";
                        }else if($tipo == "float"){
                            $opciones = $opciones_float;
                            $clase_input = "float_busqueda";
                        }else if($tipo == "int"){
                            $opciones = $opciones_float;
                            $clase_input = "int";
                        }
                        
                        if(strpos($tipo_busqueda[$i], ")))") == false){
                            $posicion2 = $posicion;
                            $posicion_tipo = strpos($opciones, ' value="'.$tipo_busqueda[$i].'"');
                            $opciones_dinamico = substr($opciones, 0, $posicion_tipo)." selected ".substr($opciones, $posicion_tipo);
                            $armado_where = armado_where($columna_sql, $tipo_busqueda[$i], $busqueda[$i], $tipo);
                            $where_busqueda .= $comienzo.$armado_where;
                            $row_busquedasGuardadas .=
                            '<div class="row row_'.$posicion.'" id="row_'.$posicion2.'">
                                <div class="col-md-4">
                                    <select id="select_'.$posicion.'" class="form-control select_busquedaGuardada">'.$opciones_dinamico.'</select>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input id="input_'.$posicion.'" class="form-control '.$clase_input.' input_busquedaGuardada" value="'.$busqueda[$i].'">
                                        <span class="input-group-addon add-on" onclick="eliminarBusqueda('.$posicion.', '.$posicion.')">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>';
                            $row_busquedasGuardadas .= getInputsDate($clase_input, $tipo_busqueda[$i], $posicion);
                        }else{
                            $tipo_busqueda_sql = explode(")))", $tipo_busqueda[$i]);
                            $busqueda_sql = explode(")))", $busqueda[$i]);
                            $tamano_sql = count($busqueda_sql);
                            $comienzo_sql = $comienzo."(";
                            $posicion2 = $posicion;
                            for($j = 0; $j < $tamano_sql; $j++){
                                $posicion_tipo = strpos($opciones, ' value="'.$tipo_busqueda_sql[$j].'"');
                                $opciones_dinamico = substr($opciones, 0, $posicion_tipo)." selected ".substr($opciones, $posicion_tipo);
                                $armado_where = armado_where($columna_sql, $tipo_busqueda_sql[$j], $busqueda_sql[$j], $tipo);
                                $where_busqueda .= $comienzo_sql.$armado_where;
                                $comienzo_sql = " or ";
                                $row_busquedasGuardadas .=
                                '<div class="row row_'.$posicion.'" id="row_'.$posicion2.'" style="padding-top:5px;">
                                    <div class="col-md-4">
                                        <select id="select_'.$posicion2.'" class="form-control select_busquedaGuardada">'.$opciones_dinamico.'</select>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input id="input_'.$posicion2.'" data-tipo="'.$tipo.'" class="form-control '.$clase_input.' input_busquedaGuardada" value="'.$busqueda_sql[$j].'">
                                            <span class="input-group-addon add-on" onclick="eliminarBusqueda('.$posicion.', '.$posicion2.')">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>';
                                $posicion2++;
                                $row_busquedasGuardadas .= getInputsDate($clase_input, $tipo_busqueda[$i], $posicion);
                            }
                            $where_busqueda .= ")";
                        }
                        $busquedasGuardadas .= 
                        '<div id="div_'.$posicion.'" class="div_busquedaGuardada" style="padding-top:10px;">
                            <label class="lab columnasBuscadas">'.$valor.'</label> <a onclick="agregoOtraBusqueda('.$posicion.', \''.$tipo.'\')"><span class="glyphicon glyphicon-plus"></span></a>
                            <input type="hidden" id="id_oculto_'.$posicion.'" value="'.$columna_sql.'">
                            '.$row_busquedasGuardadas.'
                        </div>';
                        $posicion = $posicion2+1;
                        $comienzo = " and ";
                    }
                }
                if(!empty($where)){
                    $where .= " and ".$where_busqueda;
                }else{
                    $where .= " where ".$where_busqueda;
                }
            }
        }        
        return array("posicion" => $posicion, "busquedasGuardadas" => $busquedasGuardadas, "where" => $where);
    }
}

if(!function_exists('getInputsDate')){
    function getInputsDate($clase_input, $tipo_busqueda, $posicion){
        $row_busquedasGuardadas = "";
        if($clase_input == "date"){
            if($tipo_busqueda == "13"){
                $row_busquedasGuardadas .= '
                <script>
                $(document).ready(function(){
                    $("#input_'.$posicion.'").inputmask("datetime",{
                        mask: \'1/2/y - 1/2/y\'
                    });
                });                
                </script>';
            }else if($tipo_busqueda == "14"){
                $row_busquedasGuardadas .= '
                <script>
                $(document).ready(function(){
                    $("#input_'.$posicion.'").inputmask("datetime",{
                        mask: \'2/y\'
                    });
                });
                </script>';
            }else if($tipo_busqueda == "10"){
                $row_busquedasGuardadas .= '
                <script>
                $(document).ready(function(){
                    $("#input_'.$posicion.'").addClass("int");
                });
                </script>';
            }else{
                $row_busquedasGuardadas .= '
                <script>
                $(document).ready(function(){
                    $("#input_'.$posicion.'").inputmask("datetime",{
                        mask: \'1/2/y\'
                    });
                });
                </script>';
            }
        }
        return $row_busquedasGuardadas;
    }
}