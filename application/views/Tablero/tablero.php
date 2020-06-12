<div class="content-wrapper" style="background: white !important;">
  <section class="content-header">
      <h1 id="h1_tablero">
        Tablero - <?php echo $carpeta["nombre"]; ?>
      </h1>
      <ol class="breadcrumb">
        <li class="active">Tablero</li>
      </ol>
	</section>
	<section class="content">
    <a class="btn btn-primary btn-form hidden-xs" onclick="crear_carpeta()">Crear Carpeta</a> 
    <?php if($carpeta["id_padre"] != "0"){ ?>
      <a class="btn btn-primary btn-form hidden-xs" onclick="renombrar_carpeta()">Renombrar carpeta</a> 
      <a class="btn btn-primary btn-form hidden-xs" onclick="mover_carpeta()">Mover carpeta</a> 
      <?php if($array_usuarios != "<option></option>"){ ?>
      <a class="btn btn-primary btn-form hidden-xs" onclick="compartir_carpeta()">Compartir carpeta</a> 
      <?php } ?>
      <a class="btn btn-danger btn-form hidden-xs" onclick="eliminar_carpeta()">Eliminar carpeta</a> 
    <?php } ?>
    <div class="dropdown" style="display:inline;">
      <button class="btn btn-default btn-form dropdown-toggle visible-xs" type="button" data-toggle="dropdown">Acciones <span class="caret"></span></button>
      <ul class="dropdown-menu" style="margin:43px 0 0 !important;">
          <li><a onclick="crear_carpeta()">Crear Carpeta</a></li>
          <?php if($carpeta["id_padre"] == "0"){ ?>
            <li><a onclick="renombrar_carpeta()">Renombrar carpeta</a></li>
            <li><a onclick="mover_carpeta()">Mover carpeta</a></li>
            <?php if($array_usuarios != "<option></option>"){ ?>
            <li><a onclick="compartir_carpeta()">Compartir carpeta</a></li>
            <?php } ?>
            <li><a style="background: #dd4b39; color: #fff" onclick="eliminar_carpeta()">Eliminar carpeta</a></li>
          <?php } ?>
      </ul>
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
        <a id="modalCarpeta_guardar" class="btn btn-primary btn-form">Guardar</a>
          <a class="btn btn-danger btn-form" data-dismiss="modal">Cancelar</a>
          
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
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="carpeta_hidden" value="<?php echo $carpeta["nombre"]; ?>">
<input type="hidden" id="es_padre_hidden" value="<?php echo $carpeta["es_padre"]; ?>">
<input type="hidden" id="id_carpeta_hidden" value="<?php echo $carpeta["id_carpeta"]; ?>">
<script type="text/javascript">
  var array_usuarios = `<?php echo $array_usuarios; ?>`;

  $("#modal_carpeta").keyup(function(){
    if(this.value == ""){
      marcarError(this.id, 'Campo obligatorio');
    }else{
      quitarError(this.id);
    }
  });

  function crear_carpeta(){
    $(".vistas, .listas_carpetas, .compartir_carpetas").hide();
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

  function renombrar_carpeta(){    
    $(".vistas, .listas_carpetas, .compartir_carpetas").hide();
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
    $(".vistas, .carpetas, .listas_carpetas").hide();
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
    $(".vistas, .carpetas, .compartir_carpetas").hide();
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
</script>