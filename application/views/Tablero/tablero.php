<?php 
$idGva14 =''; 
$cant_facturas ='';
$codigoCliente = '';
$razonSocial = ''; 
if (count($conf) > 0){
  $fecha_desde = $conf[0]['fecha_desde'];
  $fecha_actual = date("Y-m-d");
  if($fecha_desde == "" || $fecha_desde == "0000-00-00" || $fecha_desde == "1800-01-01" || $fecha_desde == "1900-01-01"){
    $fecha_desde = date('Y-m-d', strtotime($fecha_actual. ' - 1 year'));
  }
  $fecha_hasta = $conf[0]['fecha_hasta'];
  if($fecha_hasta == "" || $fecha_hasta == "0000-00-00" || $fecha_hasta == "1800-01-01" || $fecha_hasta == "1900-01-01"){
    $fecha_hasta = $fecha_actual;
  } 
  log_message("error", $fecha_hasta." ".$fecha_desde);
  $codigoCliente = $conf[0]['codigo_cliente'];
  $idGva14 = $conf[0]['id_gva14'];
  $razonSocial = $conf[0]['razon_social'];
}

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@1.1.1/dist/gridstack.min.css" />
<div class="content-wrapper" style="background: white !important;">
  <section class="content-header" style="">
      <div class="box-header with-border" style="/*position:fixed !important;  width:100% !important;z-index:10 !important;*/background-color: #d6d8d9;color: #1b1e21;" >
        <h3 class="box-title" style="padding-top: 8px;" id="tituloCliente" ></h3>
        <input type="date" class="form-control pull-right" placeholder="Fecha hasta" style="width: 200px"  id="fecha_hasta" value="<?php echo $fecha_hasta ?>">
        <label style="padding-top: 8px;padding-right: 10px;padding-left: 10px;" class="pull-right">Hasta</label>
        <input type="date" class="form-control pull-right" placeholder="Fecha desde" style="width: 200px"  id="fecha_desde" value="<?php echo $fecha_desde ?>">
        <label style="padding-top: 8px;padding-right: 10px;padding-left: 10px;" class="pull-right">Desde</label>
      </div>
  </section>
	<section class="content">
    <div class="grid-stack">
        <?php
        foreach ($graficos as $filaGraficos) {
          $x = $filaGraficos['x'];
          $y = $filaGraficos['y'];
          $ancho = $filaGraficos['ancho'];
          $alto = $filaGraficos['alto'];
          $nombreParaGrafico = str_replace(' ', '_', $filaGraficos['nombre']);
          $tipoGrafico = $filaGraficos['tipo'];
        ?>
            <div class="grid-stack-item" data-gs-id="<?php echo $filaGraficos['id_grafico'] ?>" data-gs-x="<?php echo $x ?>" data-gs-y="<?php echo $y ?>" data-gs-width="<?php echo $ancho ?>"  data-gs-height="<?php echo $alto ?>" id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico).'_boxbody_grid' ?>">
              <div class="grid-stack-item-content"  style="overflow: hidden  !important;" >
                <?php if ($tipoGrafico == 'grafico'){ ?>
                <div class="box" id="<?php echo $filaGraficos['id_grafico'] ?>">
                  <div class="box-header with-border" >
                    <h3 class="box-title" id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico).'_titulo' ?>"><?php echo $filaGraficos['nombre'] ?></h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico).'_boxbody' ?>" class="box-body">
                      <div id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico) ?>" align="center" style="width: 100%;height: 250px"><img src="<?php echo base_url() ?>plugin/imagenes/Preloader_2.gif"></div>
                  </div>        
                </div>
              <?php } else if ($tipoGrafico == 'indicador'){ 
                    $id_grafico_sin_titulo = strtolower($nombreParaGrafico.'_'.$tipoGrafico);
                    $id_grafico_titulo = strtolower($nombreParaGrafico.'_'.$tipoGrafico).'_titulo';
                    $id_grafico = $filaGraficos['id_grafico'];
                ?>
                  <div id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico) ?>" style="border-style: groove;" class="small-box">
                    <div class="inner" align="center">
                      <h3 id="<?php echo $id_grafico_titulo ?>" data-id="<?php echo $id_grafico ?>"><img width="30%" src="<?php echo base_url() ?>plugin/imagenes/Preloader_2.gif"></h3>
                      <p><?php echo $filaGraficos['nombre'];  ?></p>
                    </div>
                    <a style="cursor:pointer;" onclick="modalObjetivos('<?php echo $id_grafico ?>', '<?php echo $id_grafico_sin_titulo ?>')" class="small-box-footer">Objetivos <i class="fa fa-arrow-circle-right"></i></a>
                  </div>
              <?php } else if ($tipoGrafico == 'tabla'){ ?>
                <div class="box" id="<?php echo $filaGraficos['id_grafico'] ?>">
                  <div class="box-header with-border" >
                    <h3 class="box-title" id="<?php echo strtolower($nombreParaGrafico.'_'.$tipoGrafico).'_titulo' ?>"><?php echo $filaGraficos['nombre'] ?></h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                  </div>
                  <div class="box-body">
                  <div>
                  <table  class="table table-bordered dt-responsive " id="tabla_data" style="width:100% !important;">
                    <thead>
                      <tr>
                        <th>Codigo cliente</th>
                        <th>Razon social</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr class="filas" <?php $color='white'; if ('' == $codigoCliente){ $color = '#6dd66d'; } ?> style="cursor: pointer; background-color:<?php echo $color; ?>"  onclick="cambioCliente(this, '','','','')">
                            <td></td>
                            <td>Todos</td>
                          </tr>
                      <?php if (count($clientes) > 0){
                        foreach ($clientes as $fila) {
                          $cod =$fila["cod_client"];
                          $razon =$fila["razon_soci"];
                          $cant =$fila["cant_facturas"];
                          $id_gva14 =$fila["id_gva14"];
                        ?>
                        <tr class="filas" <?php $color='white'; if ($cod == $codigoCliente){   $cant_facturas =$cant; $color = '#6dd66d'; } ?>style="cursor: pointer; background-color:<?php echo $color; ?>"  onclick="cambioCliente(this, '<?php echo $id_gva14 ?>','<?php echo $cod ?>','<?php echo $razon ?>','<?php echo $cant ?>')">
                          <td><?php echo $cod ?></td>
                          <td><?php echo $razon; ?></td>
                        </tr>
                        <?php 
                        }
                      }
                      ?>  
                    </tbody>
                  </table>
                  </div>
                  <input type="hidden" id="idGva14" value="<?php echo $idGva14 ?>">
                  <input type="hidden" id="codigoCliente" value="<?php echo $codigoCliente ?>">
                  <input type="hidden" id="razonSocial" value="<?php echo $razonSocial ?>">
                  <input type="hidden" id="cant_facturas" value="<?php echo $cant_facturas ?>">
                  </div>        
                </div>
              <?php } ?>
              </div>
            </div>
        <?php }
        ?>

    </div> 
  </section>
</div>

<div class="modal fade" id="modalCarpeta" tabindex="-1" role="dialog" aria-labelledby="modalCarpeta" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalCarpeta_title"></h4>
      </div>
      <div class="modal-body">
         <div class="pull-right">
          <a id="modalCarpeta_guardar" class="btn btn-primary btn-form">Guardar</a>
          <a class="btn btn-danger btn-form" data-dismiss="modal">Cancelar</a> 
          </div>
          <div class="carpetas">
            <div class="row">
              <div class="col-md-12">
                <label class="lab">Carpeta</label>
                <input type="text" class="form-control" id="modal_carpeta">
                <div class="error_color" id="error_modal_carpeta"></div>
              </div>
            </div>
          </div>
          <div class="listas_carpetas">
            <div class="row">
              <div class="col-md-12">
                <label class="lab">Carpeta</label>                
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Buscar..." id="buscar_listas_carpetas">
                  <span class="input-group-addon add-on" id="limpiar_listas_carpetas">
                    <span class="glyphicon glyphicon-remove"></span>
                  </span>
                </div>
              </div>
              <div class="col-md-12">
                <div id="treeview-searchable"></div>
                <input type="hidden" id="id_padre_mover">
              </div>
            </div>
          </div>
          <div class="compartir_carpetas">
            <div class="row">      
              <div class="col-md-12">
                <div class="table-responsive">  
                  <table class="table" id="tabla_compartir_carpetas">
                    <thead>
                      <tr><th></th><th>Usuario</th><th>Rol</th><th>Objetivos</th></tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div> 
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <center>
                  <a onclick="agregar_compartir_carpetas()" class="btn btn-primary btn-form">Agregar Fila</a>
                </center>
              </div>
            </div>
          </div>
          <div class="vistas">
            <div class="row">
                <div class="col-md-12">
                  <label class="lab">Asociar vista a la carpeta</label>
                  <select class="form-control select2">
                  </select>
               </div>  
             </div>
          </div>
          <div class="diseno_modal">
            <br>
            <br>
            <br>
            <br>
            <div class="row">
              <div class="col-md-3">
                <label class="lab">3</label>
                <input type="checkbox" id="check_3">
              </div>
              <div class="col-md-3">
                <label class="lab">4</label>
                <input type="checkbox" id="check_4">
              </div>
              <div class="col-md-3">
                <label class="lab">6</label>
                <input type="checkbox" id="check_6">
              </div>
              <div class="col-md-3">
                <label class="lab">12</label>
                <input type="checkbox" id="check_12">
              </div>
            </div>
          </div>
  
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalObjetivos" tabindex="-1" role="dialog" aria-labelledby="modalObjetivos" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Objetivos</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_grafico_objetivo" >
        <input type="hidden" id="id_grafico_objetivo_titulo" >
        <input type="hidden" id="contador_objetivos" >
        
         <div class="pull-right">
          <a onclick="agregarFilaObjetivos()" class="btn btn-primary btn-form">Agregar</a>
          <a onclick="guardarObjetivos()" class="btn btn-primary btn-form">Guardar</a>
          <a class="btn btn-danger btn-form" data-dismiss="modal">Cancelar</a> 
          </div>
          <div class="row">
            <div class="col-md-12">
              <table id="tablaObjetivos" class="table">
                <thead>
                  <tr>
                    <td></td>
                    <td>Desde</td>
                    <td>Hasta</td>
                    <td>Valor</td>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>
  
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="carpeta_hidden" value="<?php echo $carpeta["nombre"]; ?>">
<input type="hidden" id="es_padre_hidden" value="<?php echo $carpeta["es_padre"]; ?>">
<input type="hidden" id="id_carpeta_hidden" value="<?php echo $carpeta["id_carpeta"]; ?>">

<script src="<?php echo base_url('plugin') ?>/amcharts/amcharts.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/serial.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/funnel.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/pie.js"></script>
<script src="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.min.js"></script>
<link href="<?php echo base_url('plugin') ?>/amcharts/plugins/export/export.css" type="text/css" media="all" rel="stylesheet"/>
<script src="<?php echo base_url('plugin') ?>/amcharts/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>
<script type="text/javascript">
  var grid = GridStack.init({
    "staticGrid":true
  });
  grid.on('change', function(event, items) {
    for (let i=0; i< items.length; i++){
      var element = items[i];
      let x = element.x;
      let id_grafico = element.id;
      let y = element.y;
      let alto = element.height;
      let ancho = element.width;
      let data = new Object();
      data.x = x;
      data.y = y;
      data.ancho = ancho;
      data.alto = alto;
      $.ajax({
        url:"<?php echo base_url() ?>tablero/modificarGrafico",
        type:"POST",
        data:{id_grafico:id_grafico,data:JSON.stringify(data)}
      });
    }
    
  });


  var array_usuarios = `<?php echo $array_usuarios; ?>`;

  $("#modal_carpeta").keyup(function(){
    if(this.value == ""){
      marcarError(this.id, 'Campo obligatorio');
    }else{
      quitarError(this.id);
    }
  });

  function crear_carpeta(){
    $(".vistas, .listas_carpetas, .compartir_carpetas, .diseno_modal").hide();
    $(".carpetas").show();
    quitarError('modal_carpeta');
    $("#modal_carpeta").val("");
    $("#modalCarpeta_title").html("Crear carpeta");
    $("#modalCarpeta_guardar").attr("onclick","crear_carpeta_final()");
    $("#modalCarpeta").modal("show");
  }

  function crear_carpeta_final(){
    var carpeta = $("#modal_carpeta").val();
    if(carpeta == ""){
      marcarError('modal_carpeta', 'Campo obligatorio');
    }else{
      $.ajax({
        url:"<?php echo base_url() ?>tablero/crear_carpeta",
        type:"POST",
        data:{carpeta:$("#modal_carpeta").val(), id_padre:$("#id_carpeta_hidden").val()},
        success: function(respuesta){
          if(respuesta == "OK"){
            location.reload();
          }else{
            alert(respuesta);
          }
        }
      });
    } 
  }

  function agregarFilaObjetivos(){
      var i = $('#contador_objetivos').val();
      var fila = `
          <tr id='fila_obj_${i}'>
            <td>
              <a onclick="eliminarFila(${i})"><span class="glyphicon glyphicon-trash"></span></a>
            </td>
            <td>
              <input id='desde_${i}' type='text' class='form-control' value=''>
            </td>
            <td>
              <input id='hasta_${i}' type='text' class='form-control' value=''>
            </td>
            <td>
              <select id='color_${i}' type='text' class='form-control'>
                <option selected value='Rojo'>Rojo</option>
                <option value='Verde'>Verde</option>
                <option value='Naranja'>Naranja</option>
                <option value='Azul'>Azul</option>
              </select>
            </td>
          </tr>`;
      $('#tablaObjetivos tbody').append(fila);
  }

  function sel_indicador(id_cantidad_grafico){
    var id_grafico = $('#'+id_cantidad_grafico+'_titulo').data('id');
    let cantidad_grafico = $('#'+id_cantidad_grafico+'_titulo').text();
    cantidad_grafico =cantidad_grafico.replace('$', '');
    $.ajax({
      url:"<?php echo base_url() ?>tablero/sel_objetivos_graficos",
      type:"POST",
      data:{id_grafico, cantidad_grafico},
      success: function(respuesta){
        $('#'+id_cantidad_grafico).removeClass();
        $('#'+id_cantidad_grafico).addClass('small-box');
        if (respuesta == 'Rojo'){
          $('#'+id_cantidad_grafico).removeClass();
          $('#'+id_cantidad_grafico).addClass('small-box');
          $('#'+id_cantidad_grafico).addClass('bg-red');
        } else if (respuesta == 'Verde'){
          $('#'+id_cantidad_grafico).removeClass();
          $('#'+id_cantidad_grafico).addClass('small-box');
          $('#'+id_cantidad_grafico).addClass('bg-green');
        } else if (respuesta == 'Naranja'){
          $('#'+id_cantidad_grafico).removeClass();
          $('#'+id_cantidad_grafico).addClass('small-box');
          $('#'+id_cantidad_grafico).addClass('bg-yellow');
        } else if (respuesta == 'Azul'){
          $('#'+id_cantidad_grafico).addClass('bg-aqua');
        } 
      }
    });
  }

  function guardarObjetivos(){
    let id_grafico = $('#id_grafico_objetivo').val();
    let id_grafico_titulo = $('#id_grafico_objetivo_titulo').val();
    var datos = new Array();
    $("#tablaObjetivos tbody tr").each(function(){
      let id = this.id;
      let cont = id.substr(9,id.length);
      datos.push({id_grafico:id_grafico, desde:$('#desde_'+cont).val(), hasta:$('#hasta_'+cont).val(), color:$('#color_'+cont).val()});
    });
    $.ajax({
      url:"<?php echo base_url() ?>tablero/guardar_objetivos_graficos",
      type:"POST",
      data:{datos: JSON.stringify(datos), id_grafico:id_grafico},
      success: function(respuesta){
          $('#modalObjetivos').modal('hide');
          sel_indicador(id_grafico_titulo);
      }
    });
  }

  function eliminarFila(cont){
    $('#fila_obj_'+cont).remove();
  }

  function modalObjetivos(id_grafico, id_grafico_titulo){
    $('#id_grafico_objetivo').val(id_grafico);
    $('#id_grafico_objetivo_titulo').val(id_grafico_titulo);
    $.ajax({
      url:"<?php echo base_url() ?>tablero/get_objetivos_graficos",
      type:"POST",
      dataType: "json",
      data:{id_grafico},
      success: function(respuesta){
        $('#tablaObjetivos tbody tr').remove();
        for (var i =0; i < respuesta.length; i++) {
          var fila = `
          <tr id='fila_obj_${i}'>
            <td>
              <a onclick="eliminarFila(${i})"><span class="glyphicon glyphicon-trash"></span></a>
            </td>
            <td>
              <input id='desde_${i}' type='text' class='form-control' value='${respuesta[i]['desde']}'>
            </td>
            <td>
              <input id='hasta_${i}' type='text' class='form-control' value='${respuesta[i]['hasta']}'>
            </td>
            <td>
              <select id='color_${i}' type='text' class='form-control'>
                <option selected value='Rojo'>Rojo</option>
                <option value='Verde'>Verde</option>
                <option value='Naranja'>Naranja</option>
                <option value='Azul'>Azul</option>
              </select>
            </td>
          </tr>`;
          $('#tablaObjetivos tbody').append(fila);
          $('#color_'+i).val(respuesta[i]['color']);
        }
         $('#contador_objetivos').val(i);
        $('#modalObjetivos').modal('show');
      }
    });
    
  }

  function renombrar_carpeta(){    
    $(".vistas, .listas_carpetas, .compartir_carpetas, .diseno_modal").hide();
    $(".carpetas").show();
    quitarError('modal_carpeta');
    $("#modal_carpeta").val($("#carpeta_hidden").val());
    $("#modalCarpeta_title").html("Renombrar carpeta");
    $("#modalCarpeta_guardar").attr("onclick","renombrar_carpeta_final()");
    $("#modalCarpeta").modal("show");
  }

  function renombrar_carpeta_final(){
    var carpeta = $("#modal_carpeta").val();
    if(carpeta == ""){
      marcarError('modal_carpeta', 'Campo obligatorio');
    }else{
      $.ajax({
        url:"<?php echo base_url() ?>tablero/renombrar_carpeta",
        type:"POST",
        data:{carpeta, id_carpeta:$("#id_carpeta_hidden").val()},
        success: function(respuesta){
          if(respuesta == "OK"){
            location.reload();
          }else{
            alert(respuesta);
          }
        }
      });
    }
  }

  function eliminar_carpeta(){
    if(confirm("Desea eliminar la carpeta '"+$("#carpeta_hidden").val()+"'?")){
      var id_carpeta = $("#id_carpeta_hidden").val();
      var eliminar_hijos = -1;
      if($("#es_padre_hidden").val() == "1"){
        eliminar_hijos = 0;
        if(confirm("La carpeta tiene '"+$("#carpeta_hidden").val()+"' dependencias, desea eliminarlas también?")){
          eliminar_hijos = 1;
        }
      }
      $.ajax({
        url:"<?php echo base_url() ?>tablero/eliminar_carpeta",
        type:"POST",
        data:{id_carpeta, eliminar_hijos},
        success: function(respuesta){
          if(respuesta == "OK"){
            location.href = '<?php echo base_url() ?>tablero';
          }else{
            alert(respuesta);
          }
        }
      });
    }
  }

  function compartir_carpeta(){
    $(".vistas, .carpetas, .listas_carpetas, .diseno_modal").hide();
    $(".compartir_carpetas").show();
    $("#modalCarpeta_title").html("Compartir carpeta");
    $("#modalCarpeta_guardar").attr("onclick","compartir_carpeta_final()");
    $("#tabla_compartir_carpetas tbody tr").remove();
    agregar_compartir_carpetas();
    $("#modalCarpeta").modal("show");    
  }

  function compartir_carpeta_final(){
    var id_carpeta = $("#id_carpeta_hidden").val();
    var array = new Array();
    var objeto = new Object();
    $("#tabla_compartir_carpetas tbody tr").each(function(){
      if($(this).children(".usuario").val() != ""){
        objeto.nombreUsuario = $(this).children(".usuario").val();
        objeto.rol = $(this).children(".rol").val();
        objeto.objetivos = $(this).children(".objetivos").val();
        array.push(objeto);
      }
    })
    if(array.length > 0){
      $.ajax({
        url:"<?php echo base_url() ?>tablero/compartir_carpeta",
        type:"POST",
        data:{array:JSON.stringify(array), id_carpeta},
        success: function(respuesta){
          if(respuesta == "OK"){
            $("#modalCarpeta").modal("hide");
          }else{
            alert(respuesta);
          }
        }
      });
    }
  }

  function agregar_compartir_carpetas(){
    $("#tabla_compartir_carpetas tbody").append(
    `<tr>
      <td><a onclick='eliminar_compartir_carpetas(this)'><span class='glyphicon glyphicon-trash'></span></a></td>
      <td><select class='form-control usuario'>${array_usuarios}</select></td>
      <td><select class='form-control rol'><option>Lectura</option><option>Lectura y escritura</
      option></select></td>
      <td><select class='form-control rol'><option>Si</option><option>No</
      option></select></td>
    </tr>`);
  }

  function eliminar_compartir_carpetas(objeto){
    if(confirm("Desea eliminar la fila?")){
      $(objeto).closest("tr").remove();
    }
  } 

  var $checkableTree;
  function mover_carpeta(){
    $(".vistas, .carpetas, .compartir_carpetas, .diseno_modal").hide();
    $(".listas_carpetas").show();
    $("#modalCarpeta_title").html("Mover carpeta");  
    $checkableTree = $('#treeview-searchable').treeview({
      data: getTree(),
      showIcon: false,
      showCheckbox: true,
      onNodeChecked: function(event, node) {
        if(node.id != $("#id_carpeta_hidden").val()){
          $("#id_padre_mover").val(node.id);
          $checkableTree.treeview('uncheckNoNode', [ node, { silent: false }]);
        }else{
          $checkableTree.treeview('uncheckNode2', [ node, { silent: false }]);
        }
      }
    });
    $checkableTree.treeview('uncheckAll', { silent: false });
    
    $("#modalCarpeta_guardar").attr("onclick","mover_carpeta_final()");
    $("#modalCarpeta").modal("show");
  }

  function mover_carpeta_final(){
    var id_padre = $("#id_padre_mover").val(); //OBTENER DEL TREEVIEW
    var id_carpeta = $("#id_carpeta_hidden").val();
    $.ajax({
      url:"<?php echo base_url() ?>tablero/mover_carpeta",
      type:"POST",
      data:{id_padre, id_carpeta},
      success: function(respuesta){
        if(respuesta == "OK"){
          location.reload();
        }else{
          alert(respuesta);
        }
      }
    });
  }

  var lastPattern = '';
  var tree = <?php echo json_encode($carpetas); ?>;
  function getTree() {
    return tree;
  }

  function reset(tree) {
    tree.collapseAll();
    tree.enableAll();
  }

  function collectUnrelated(nodes) {
    var unrelated = [];
    $.each(nodes, function (i, n) {
      if (!n.searchResult && !n.state.expanded) {
        unrelated.push(n.nodeId);
      }
      if (!n.searchResult && n.nodes) {
        $.merge(unrelated, collectUnrelated(n.nodes));
      }
    });
    return unrelated;
  }

  var search = function (e) {
    var pattern = $('#buscar_listas_carpetas').val();
    if (pattern === lastPattern) {
      return;
    }
    lastPattern = pattern;
    var tree = $('#treeview-searchable').treeview(true);
    reset(tree);
    if (pattern.length == 0) {
      tree.clearSearch();
    } else {
      tree.search(pattern);
      var roots = tree.getSiblings(0);
      roots.push(tree.getNode(0));
      var unrelated = collectUnrelated(roots);
      tree.disableNode(unrelated, {silent: true});
    }
  };

  $('#buscar_listas_carpetas').on('keyup', search);

  $('#limpiar_listas_carpetas').on('click', function (e) {
    $('#buscar_listas_carpetas').val('');
    var tree = $('#treeview-searchable').treeview(true);
    reset(tree);
    tree.clearSearch();
  });

  function graficoTorta(id, dataGrafico){
    var chart = am4core.create(id, am4charts.PieChart);
    chart.data = dataGrafico;
    var series = chart.series.push(new am4charts.PieSeries());
    series.dataFields.value = "valor";
    series.dataFields.category = "categoria"; 
  }

  function graficoBarras(id, dataGrafico){
    var chart = am4core.create(id, am4charts.XYChart);
    chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
    chart.data = dataGrafico;

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.dataFields.category = "categoria";
    categoryAxis.renderer.minGridDistance = 40;
    categoryAxis.fontSize = 11;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    /*valueAxis.max = 1000;*/
    valueAxis.strictMinMax = true;
    valueAxis.renderer.minGridDistance = 30;
    
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.categoryX = "categoria";
    series.dataFields.valueY = "valor";
    series.columns.template.tooltipText = "{valueY.value}";
    series.columns.template.tooltipY = 0;
    series.columns.template.strokeOpacity = 0;

    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
    series.columns.template.adapter.add("fill", function(fill, target) {
      return chart.colors.getIndex(target.dataItem.index);
    });
  }

  $(document).ready(function() {
     $("#tabla_data").DataTable({
      "pageLength": 10, 
      "language": { "info": "_START_ a _END_ de _TOTAL_ registros", "zeroRecords": "", "emptyTable": "", "search": "", "searchPlaceholder": "Buscar...", "paginate": { "previous": "<", "next": ">"}, "sLengthMenu": "_MENU_" }
    }); 
    cargarGraficos(); 

  });

  function cambioCliente(obj, id_gva14,cod,razon,cant){
    $('.filas').css('background-color', 'white');
    $(obj).css('background-color', '#6dd66d');
    $('#idGva14').val(id_gva14);
    $('#cant_facturas').val(cant);
    $('#codigoCliente').val(cod);
    $('#razonSocial').val(razon);
    cargarGraficos();
  }

  $("#fecha_desde, #fecha_hasta").change(function(){
    cargarGraficos();
  });
  

  function cargarGraficos(){
    let idGva14 = $('#idGva14').val();
    let cant_facturas = $('#cant_facturas').val();
    let codigoCliente = $('#codigoCliente').val();
    let razonSocial = $('#razonSocial').val(); 
    let fecha_desde = $('#fecha_desde').val(); 
    let fecha_hasta = $('#fecha_hasta').val(); 
    let arrayGraficos = {fecha_desde:fecha_desde,fecha_hasta:fecha_hasta, codigo_cliente:codigoCliente, empresa:"<?php echo $this->session->userdata('empresa') ?>"}
    $.ajax({
        url: "<?php echo base_url() ?>tablero/guardar_configuracion_usuario",
        type: "POST",
        data:{idGva14, codigoCliente, razonSocial, fecha_hasta, fecha_desde}
      });

    let texto = '';
    if (idGva14 ==''){
      if (cant_facturas > 1){
        texto = 'Todos -'+cant_facturas+' Facturas';
      } else {
        texto = 'Todos -'+cant_facturas+' Factura';  
      }
      
    } else {
       if (cant_facturas > 1){
        texto = codigoCliente+' - '+razonSocial+' - '+cant_facturas+' Facturas';
       } else {
          texto = codigoCliente+' - '+razonSocial+' - '+cant_facturas+' Factura';
       }
    }
    $('#tituloCliente').text(texto);
     if ($('#vencido_vs_no_vencido_grafico').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/getVencidoNoVencidoCliente",
        type: "POST",
        data: arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          let deuda = respuesta[0]['deuda'];
          let vencido = respuesta[0]['vencido'];
          let no_vencido = respuesta[0]['no_vencido'];
          if (deuda == undefined){
            deuda = 0;
          }
          if (vencido == undefined){
            vencido = 0;
          }
          if (no_vencido == undefined){
            no_vencido = 0;
          }
          $('#total_deuda_indicador_titulo').text('$'+deuda);
          sel_indicador('total_deuda_indicador');
          $('#deuda_vencida_indicador_titulo').text('$'+vencido);
          sel_indicador('deuda_vencida_indicador');
          $('#deuda_a_vencer_indicador_titulo').text('$'+no_vencido);
          sel_indicador('deuda_a_vencer_indicador');
          var dataGrafico = [
            {categoria: "Vencido",valor: vencido},
            {categoria: "No Vencido", valor: no_vencido}
          ];
          graficoTorta('vencido_vs_no_vencido_grafico', dataGrafico);
        }
      });
    }

    if ($('#distribucion_por_dias_de_vencimiento_grafico').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/grafico_distribucion_dias_venc",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          console.log(respuesta);
          graficoBarras('distribucion_por_dias_de_vencimiento_grafico', respuesta);
        }
      });
      
    }

    if ($('#dias_vs_meses_grafico').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/grafico_dias_de_mora",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          graficoBarras('dias_vs_meses_grafico', respuesta);
        }
      });
    }

    if ($('#ponderado_de_pago_grafico').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/grafico_ponderado_de_pago",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          graficoBarras('ponderado_de_pago_grafico', respuesta);
        }
      });
    }


    if ($('#ponderado_de_pago_indicador').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/get_ponderado_de_pago",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          $('#ponderado_de_pago_indicador_titulo').text(respuesta[0]['ponderado_de_pago']);
          sel_indicador('ponderado_de_pago_indicador');
        }
      });
    }

    if ($('#antiguedad_de_deuda_indicador').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/get_antiguedad_de_pago",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          $('#antiguedad_de_deuda_indicador_titulo').text(respuesta[0]['antiguedad_de_pago']);
          sel_indicador('antiguedad_de_deuda_indicador');
        }
      });
    }

    if ($('#dias_de_mora_indicador').length > 0){
      $.ajax({
        url: "<?php echo $this->session->userdata('dominio') ?>/api/get_dias_de_mora",
        type: "POST",
        data:arrayGraficos,
        dataType: "json",
        success: function(respuesta){
          $('#dias_de_mora_indicador_titulo').text(respuesta[0]['dias_de_mora']);
          sel_indicador('dias_de_mora_indicador');
        }
      });
    }

  }

</script>