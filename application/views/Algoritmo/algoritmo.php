<div class="content-wrapper" style="background: white !important;">	
    <section class="content-header">
        <h1>
          Algoritmo
        </h1>
        <ol class="breadcrumb">
          <li class="active">Formulario</li>
        </ol>
  	</section>
  	<section class="content">  		
      <div class="formulario">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#general">Formulario</a></li>
          </ul>
          <div class="tab-content">
            <div id="general" class="tab-pane fade in active">
              <div class="row">
                <div class="col-md-4">
                  <label class="lab">Cliente</label>
                  <input type="text" class="form-control" id="cliente" readonly onfocus="modal('Cliente')" value="">
                  <input type="hidden" id="id_cliente" value="">
                  <input type="hidden" id="cod_cliente" value="">
                  <input type="hidden" id="cuit" value="">
                  <div class="error_color" id="error_cliente"></div>
                </div>
                <div class="col-md-4">
                  <label class="lab">Condicion de Venta</label>
                  <input type="text" class="form-control" id="condicion_de_venta" readonly onfocus="modal('CondicionDeVenta')" value="">
                  <input type="hidden" id="id_condicion_de_venta" value="">
                  <div class="error_color" id="error_condicion_de_venta"></div>
                </div>
                <div class="col-md-4">
                  <label class="lab">Monto</label>
                  <input type="text" class="form-control float" id="monto">
                  <div class="error_color" id="error_monto"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label class="lab">Tipo de operacion</label>
                  <select class="form-control" id="se_le_vendio">
                    <option>Producto</option>
                    <option>Servicio</option>
                  </select>
                  <div class="error_color" id="error_situacion_entidad"></div>
                </div>
                <div class="col-md-4">
                  <label class="lab">Peor situacion con entidad <a title="Ayuda" onclick="abrirAyudaBCRA()"><span class="glyphicon glyphicon-info-sign"></span></a></label>
                  <select class="form-control" id="situacion_entidad">
                    <option value=""></option>
                    <option value ="1">1 | En situacion normal</option>
                    <option value ="2">2 | Con seguimiento especial</option>
                    <option value ="3">3 | Con problemas</option>
                    <option value ="4">4 | Con alto riesgo de insolvencia</option>
                    <option value ="5">5 | Irrecuperable</option>
                    <option value ="6">6 | Irrecuperable por disposicion tecnica</option>
                  </select>
                  <div class="error_color" id="error_situacion_entidad"></div>
                </div>
                <div class="col-md-4">
                  <label class="lab">Monto de peor situacion con entidad <a title="Ayuda" onclick="abrirAyudaBCRA()"><span class="glyphicon glyphicon-info-sign"></span></a></label>
                  <input type="text" class="form-control float" id="monto_entidad" value="">
                  <div class="error_color" id="error_monto_entidad"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div id="datos_adicionales"></div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <br>
                  <a onclick="predecir()" id="predecir" class="btn btn-primary btn-form">Predecir comportamiento de pago</a>  <div style="font-weight: 500; line-height: 1.1; display:inline; font-size: 30px;" id="div_prediccion"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	</section>
</div>

<div class="modal fade in" id="modalAyudaBCRA" tabindex="-1" role="dialog" aria-labelledby="formBuscar1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" >
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="formBuscar1">Ayuda</h4> 
      </div>        
      <div class="modal-body">
        <div>
          <h4>Acceder a <a target="_blank" href="https://www.bcra.gob.ar/bcrayvos/Situacion_Crediticia.asp">Central de deudores del BCRA</a></h4>
        </div>
        <div>
          <h4>Paso 1: Ingresar el CUIT</h4>
          <img src="<?php echo base_url('plugin') ?>/imagenes/PASO_1_BCRA.PNG">
        </div>
        <div>
          <h4>Paso 2: Tomar fila que tenga peor situacion con la entidad financiera</h4>
          <img src="<?php echo base_url('plugin') ?>/imagenes/PASO_2_BCRA.PNG">
        </div>        
      </div>
    </div>
  </div>  
</div>  

<input type="hidden" id="categoria_iva">
<input type="hidden" id="rubro">
<input type="hidden" id="cantidad_empleados">
<input type="hidden" id="antiguedad">
<input type="hidden" id="importe_comp_vencidos_2_anos">

<script type="text/javascript">
  var cuotas = new Array();

  function abrirAyudaBCRA(){
    $("#modalAyudaBCRA").modal("show");
  }

  $(document).on("keypress", ".int", function(e){  
    var key = e.charCode;
    return key >= 48 && key <= 57;
  })

  $(document).on("blur", ".int", function(e){  
    if(isNaN(this.value)){
      this.value = "";
    }
  })  

  function getSelectionStart(o) {
    if (o.createTextRange) {
      var r = document.selection.createRange().duplicate();
      r.moveEnd('character', o.value.length);
      if (r.text == '') return o.value.length;
      return o.value.lastIndexOf(r.text);
    } else return o.selectionStart;
  }

  /*VALIDA QUE LO PEGADO EN LOS CAMPOS IMPORTES CUMPLA CON LO QUE ES UN FLOAT*/
  $(document).on("paste", ".float", function(e){
    var valor = e.originalEvent.clipboardData.getData('Text').trim();
    if(valor.indexOf(".") > -1 && valor.indexOf(",") > -1){
      valor= valor.replace(new RegExp(escapeRegExp('.'), 'g'), '');
      valor= valor.replace(new RegExp(escapeRegExp(','), 'g'), '.');
    }else if(valor.indexOf(",") !=='-1'){
      valor= valor.replace(new RegExp(escapeRegExp(','), 'g'), '.');
    }
    valor=parseFloat(valor);
    if (!isNaN(valor)){ 
      valor = valor.toFixed(2);
      $(this).val(valor);
      return false;
    }else {
      return false;
    }
  });    

  /*VALIDA QUE LO PEGADO EN LOS CAMPOS IMPORTES CUMPLA CON LO QUE ES UN FLOAT*/
  $(document).on("keypress", ".float", function(e){
    var id = $(this).attr("id");
    var o = this;
    var teclaPulsada = window.event ? window.event.keyCode:e.which;
    var s = getSelectionStart(o); 
    var valor = o.value;    
    var startPos = o.selectionStart;
    var endPos = o.selectionEnd;
      
    if(startPos == 0 && endPos == valor.length){
      return /\d/.test(String.fromCharCode(teclaPulsada));
    }   
    
    if(teclaPulsada==13 || (teclaPulsada==46 && valor.indexOf(".")==-1)){
      var res=true;
      return res;
    }

    if (teclaPulsada == 44 && valor.indexOf(".") == -1){      
      var principio = valor.substring(0,s);
      var fin = valor.substr(s);        
          o.value = principio+"."+fin;
      return false;
      }

    if(valor.indexOf(".")!=='-1'){
      if(s > valor.indexOf(".")){
        if((valor.substr(valor.indexOf(".")).length) == '3'){
          return false;
        }
      }
    }   
    if (/\d/.test(String.fromCharCode(teclaPulsada))){
      var res=true;
      return res;      
    }
    return /\d/.test(String.fromCharCode(teclaPulsada));
  });


  function seleccionarCondicionDeVenta(id){    
    $.ajax({
      url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarCondicionDeVenta",
      type: "POST",
      data:{id, empresa},
      dataType: "json",
      success: function(respuesta){        
        $("#id_condicion_de_venta").val(id);
        $("#condicion_de_venta").val(respuesta[0]["DESC_COND"]);
        cuotas = respuesta;
        $("#modal").modal("hide");
      }
    });
  }

  function seleccionarCliente(id){
    $.ajax({
      url: "<?php echo $this->session->userdata('dominio') ?>/api/seleccionarCliente",
      type: "POST",
      data:{id, empresa},
      dataType: "json",
      success: function(respuesta){
        $("#id_cliente").val(respuesta[0]["id_cliente"]);
        $("#cod_cliente").val(respuesta[0]["cod_cliente"]);
        $("#cliente").val(respuesta[0]["cliente"]);
        var cuit = respuesta[0]["cuit"];
        $("#cuit").val(cuit);
        
        $.ajax({
          url: "<?php echo base_url() ?>seguimiento/obtenerDatosCliente",
          type: "POST",
          data:{cuit, id, empresa},
          dataType: "json",
          success: function(datos){
            
            var categoria_iva = datos['categoria_iva'];
            $("#categoria_iva").val(categoria_iva);
            if(categoria_iva == "EsRI"){
              categoria_iva = "Responsable Inscripto";
            }else if(categoria_iva == "EsRI"){
              categoria_iva = "Monotributo";
            }else if(categoria_iva == "EsExento"){
              categoria_iva = "Exento";
            }
            
            var rubro = datos['rubro'];
            $("#rubro").val(rubro); 
            
            var cantidad_empleados = datos['cantidad_empleados'];         
            $("#cantidad_empleados").val(cantidad_empleados);
            
            var antiguedad = datos['antiguedad'];
            $("#antiguedad").val(antiguedad);
            
            var importe_comp_vencidos_2_anos = datos['importe_comp_vencidos_2_anos'];            
            $("#importe_comp_vencidos_2_anos").val(importe_comp_vencidos_2_anos);
            if(importe_comp_vencidos_2_anos == null){
              importe_comp_vencidos_2_anos = "0";
            }
            var html = `<br><label>CUIT:</label> ${cuit}<br><label>Categoria Iva:</label> ${categoria_iva}<br> <label>Cantidad de empleados:</label> ${cantidad_empleados}<br> <label>Antiguedad:</label> ${antiguedad}<br> <label>Promedio de comprobantes vencidos:</label> ${importe_comp_vencidos_2_anos}`;
            $("#datos_adicionales").html(html);
          }
        });
        $("#modal").modal("hide"); 
      }
    });
  }  

  function predecir(){
    $("#predecir").html("Cargando...");
    $("#predecir").prop("disabled", "true");
    $('#predecir').removeAttr('onclick');
    var ok = true;

    var cliente = $("#id_cliente").val();
    if(cliente == ""){
      ok = false;
      marcarError("cliente", "Campo obligatorio");
    }

    var condicion_de_venta = $("#id_condicion_de_venta").val();
    if(condicion_de_venta == ""){
      ok = false;
      marcarError("condicion_de_venta", "Campo obligatorio");
    }

    var monto = $("#monto").val();
    if(monto != ""){
      if(monto == 0){
        ok = false;
        marcarError("monto", "Debe ser mayor a 0");  
      }
    }else{
      ok = false;
      marcarError("monto", "Campo obligatorio");
    }

    var ponderado_cuotas = "0";
    var importe_vt = 0;
    var dias = 0;
    var dias_final = 0;
    for(var i = 0; i < cuotas.length; i++){
      dias = cuotas[0]["A_VENCER"];
      importe_vt = (cuotas[0]["PORC_MONTO"]/100)*monto;
      dias_final = dias_final + (dias*importe_vt);
    }

    var ponderado_cuotas = dias_final / monto;
    if(ponderado_cuotas >= 0 && ponderado_cuotas <= 6){
      ponderado_cuotas = 'CONDICION A'; 
    }else if(ponderado_cuotas >= 7 && ponderado_cuotas <= 13){
      ponderado_cuotas = 'CONDICION B'; 
    }else if(ponderado_cuotas >= 14 && ponderado_cuotas <= 29){
      ponderado_cuotas = 'CONDICION C'; 
    }else if(ponderado_cuotas >= 30 && ponderado_cuotas <= 59){
      ponderado_cuotas = 'CONDICION D'; 
    }else if(ponderado_cuotas >= 60){
      ponderado_cuotas = 'CONDICION E';
    }else{
      ponderado_cuotas = 'CONDICION A';
    }
    var categoria_iva = $("#categoria_iva").val();
    var antiguedad = $("#antiguedad").val();    
    var cantidad_empleados = $("#cantidad_empleados").val();
    var rubro = $("#rubro").val();
    var importe_comp_vencidos_2_anos = $("#importe_comp_vencidos_2_anos").val();
    if(importe_comp_vencidos_2_anos == ''){
      importe_comp_vencidos_2_anos = null;
    }

    var se_le_vendio = $("#se_le_vendio").val();
    var situacion_entidad = $("#situacion_entidad").val();
    if(1 == situacion_entidad){
      situacion_entidad = "En situacion normal";
    } else if(2 == situacion_entidad){
      situacion_entidad = "Con seguimiento especial";
    } else if(3 == situacion_entidad){
      situacion_entidad = "Con problemas";
    } else if(4 == situacion_entidad){
      situacion_entidad = "Con alto riesgo de insolvencia";
    } else if(5 == situacion_entidad){
      situacion_entidad = "Irrecuperable";
    } else if(6 == situacion_entidad){
      situacion_entidad = "Irrecuperable por disposicion tecnica";
    } else {
      situacion_entidad = "En situacion normal";
    }
    var monto_entidad = $("#monto_entidad").val(); 
    if(monto_entidad != ""){
      if(monto_entidad == 0){
        monto_entidad = 1;
      }
    }else{
      monto_entidad = 1;
    } 

    var objeto = {importe_comp_vencidos_2_anos, ponderado_cuotas, promedio_importe_comp_2_anos:monto, situacion:situacion_entidad, monto:monto_entidad, se_le_vendio, cantidad_empleados, antiguedad, rubro, categoria_iva};
   
    $("#div_prediccion").css("color", "red");
    $("#div_prediccion").html("Va ser Deudor");
    ok = false;
    if(ok){  
      $.ajax({
        url: "<?php echo base_url() ?>seguimiento/predecir",
        type: "POST",
        dataType: 'json',
        data:{objeto: JSON.stringify(objeto)},
        success: function(respuesta){
          var html_respuesta = "";
          if(respuesta['respuesta'] == "0"){
            html_respuesta = "No va ser Deudor";
            $("#div_prediccion").css("color", "black");
          }else{
            html_respuesta = "Va ser Deudor";
            $("#div_prediccion").css("color", "red");
          }
          $("#div_prediccion").html(html_respuesta);
          $("#predecir").html("Predecir comportamiento de pago");
          $("#predecir").prop("disabled", "false");
          $('#predecir').attr('onclick', "predecir()");
        }
      });
    }else{
      $("#predecir").html("Predecir comportamiento de pago");
      $("#predecir").prop("disabled", "false");
      $('#predecir').attr('onclick', "predecir()");
    }
  }

</script>