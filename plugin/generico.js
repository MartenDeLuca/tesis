$(document).ready(function(){
  /* boton de arriba */
  $('.irArriba').click(function(){
    $('body, html').animate({scrollTop: '0px'},300);
  });

  $(window).scroll(function(){
    $('#menuDerecho').css('display', 'none');
      if($(this).scrollTop() > 0){
        $('.irArriba').slideDown(300);
        $('.fijo').addClass('fijo_scroll');
      }else{
        $('.irArriba').slideUp(300);
        $('.fijo').removeClass('fijo_scroll');
      }
  });
 

  /* SELECCION TODO EN UN CAMPO DE TEXTO */
  /*$(document).on("click","input[type='text']", function(){             
    this.select();
  }); 
*/
  /* SELECCIONE EL INPUT APENAS LO ABRA EL MODAL */
  var ultimoModalAbierto='';
  var ultimoModalCerrado='';
  $('.modal').on('shown.bs.modal', function () {
    if (ultimoModalAbierto != this.id){
      ultimoModalAbierto= this.id;
      modalesAbierto++; 
    }
    $('input:not([readonly]):visible:enabled:first', this).focus();
    
    if (modalesAbierto > 0){
      $('body').addClass('modal-open');
    } else {
      $('body').css('overflow', 'auto');
    }
  })

  /*$('body').on('shown.bs.modal', '.modal', function () {
    abierto++;
    $('input:visible:enabled:first', this).focus();
    if (abierto > 0){
      $('body').addClass('modal-open');
    }
  })  */
  
  $('body').on('hidden.bs.modal', '.modal', function () { 
    if (ultimoModalCerrado != this.id){
      ultimoModalCerrado= this.id;
      modalesAbierto--; 
    }
    if (modalesAbierto == 0){
      $('body').css('overflow', 'auto');
    } else {
      $('body').addClass('modal-open');
    }
    //$(".datosAlBuscar").html("");
  })

 /* $('.modal').on('shown.bs.modal', function (e) {
    
  })
*/
  /*$('.modal').on('hidden.bs.modal', function (e) {
   
  })*/
});

var modalesAbierto=0;
var down=false;
var scrollLeft=0;
var x = 0;

$('body').on('mousedown', '.moverMouse', function(e) {
  down = true;
  scrollLeft = this.scrollLeft;
  x = e.clientX;
});

$('body').on('mouseup', '.moverMouse', function(e) {
  down = false;
});

$('body').on('mousemove', '.moverMouse', function(e) {
  if (down) {          
    this.scrollLeft = scrollLeft + x - e.clientX;
  }
});

$('body').on('mouseleave', '.moverMouse', function(e) {
  down = false;
});



/* Inicio - Columnas */
var options = [];

$( '.dropdown-columnas a' ).on( 'click', function( event ) {

   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
      
   return false;
});
/* Fin - Columnas */

function vaciarNotificaciones(idUsuario){
  var base_url = document.getElementById("base_url").value;
  $.ajax({
      url:base_url+"principal/vaciarNotificaciones/"+idUsuario,
      type: 'POST',     
  }).done(function(respuesta){
      if (respuesta == 'ok'){
        document.title = "CRMFlow";
        $('#notificaciones').hide();
      }
  })
}


var arrayNotasAsociadas = new Array();
var posicionArrayNotasAsociadas = 0;
function obtenerNotasAsociadas(id, relacion){
  $.ajax({
    url: document.getElementById("base_url").value+"Nota/obtenerNotasAsociadas",
    type: "POST",
    dataType : "json",    
    data:{id:id, relacion:relacion},
    success: function (respuesta){      
      arrayNotasAsociadas = new Array();
      posicionArrayNotasAsociadas = 0;
      cantidadVecesLlamadoShow = 0;
      cantidadVecesLlamadoHide = 0;
      var tamano = respuesta.length;
      arrayNotasAsociadas = respuesta;    
      //  
      $('#confirmar_alerta_boton').show();
      if(tamano > 0){
        $("#formAlertaNotas").modal("show");
      }            
    }
  });
}

var cantidadVecesLlamadoShow = 0;
var cantidadVecesLlamadoHide = 0;

/*cuando se cierra*/
$(document).on('hidden.bs.modal', '#formAlertaNotas', function (e) {
  cantidadVecesLlamadoShow = 0;
  var tamano = arrayNotasAsociadas.length;
  if(tamano > posicionArrayNotasAsociadas && cantidadVecesLlamadoHide == 0){
    $("#formAlertaNotas").modal("show");
  }
  cantidadVecesLlamadoHide = 1; 
})

/*cuando se abre*/
$(document).on('shown.bs.modal', '#formAlertaNotas', function (e) {
  var tamano = arrayNotasAsociadas.length;  
  if(tamano > posicionArrayNotasAsociadas && cantidadVecesLlamadoShow == 0){    
    var i = posicionArrayNotasAsociadas;
    var descripcion = arrayNotasAsociadas[i]['descripcion'];
    var asunto = arrayNotasAsociadas[i]['asunto'];
    CKEDITOR.instances['notas_descripcion_alerta'].setData(descripcion);    
    document.getElementById("Asunto_Nota_Alerta").value = asunto;
    posicionArrayNotasAsociadas++;
  }
  cantidadVecesLlamadoHide = 0;
  cantidadVecesLlamadoShow = 1;
})


var arrayAlertasGenerales = new Array();
var posicionArrayAlertasGenerales = 0;
var vieneDeUnCampoAlerta = 0;
function getAlertasAsociadas(id, relacion, relacion_singular, model, vieneDeUnCampo){
  $.ajax({
    url: document.getElementById("base_url").value+"Alerta/getAlertasAsociadas",
    type: "POST",
    dataType : "json",    
    data:{id:id, relacion:relacion, relacion_singular:relacion_singular, model:model},
    success: function (respuesta){      
      arrayAlertasGenerales = new Array();
      posicionArrayAlertasGenerales = 0;
      cantidadVecesLlamadoShowAlerta = 0;
      cantidadVecesLlamadoHideAlerta = 0;
      vieneDeUnCampoAlerta = vieneDeUnCampo;
      var tamano = respuesta.length;
      arrayAlertasGenerales = respuesta;  
      if(tamano > 0){       
        $("#formAlertaGeneral").modal("show");
      }            
    }
  });
}

var cantidadVecesLlamadoShowAlerta = 0;
var cantidadVecesLlamadoHideAlerta = 0;

/*cuando se cierra*/
$(document).on('hidden.bs.modal', '#formAlertaGeneral', function (e) {
  cantidadVecesLlamadoShowAlerta = 0;
  var tamano = arrayAlertasGenerales.length;
  $("#modal-header-formAlertaGeneral").css("background-color", "");
  $("#formAlertaGeneralAux").css("color", "black");
  if(tamano > posicionArrayAlertasGenerales && cantidadVecesLlamadoHideAlerta == 0){
    $("#formAlertaGeneral .modal-footer").hide();
    $("#formAlertaGeneral").modal("show");
  }
  cantidadVecesLlamadoHideAlerta = 1; 
  if (esConfirma){
    confirmarAlertaCortar();
  }
})

var esConfirma=false;
/*cuando se abre*/
$(document).on('shown.bs.modal', '#formAlertaGeneral', function (e) {
  var tamano = arrayAlertasGenerales.length;  
  if(tamano > posicionArrayAlertasGenerales && cantidadVecesLlamadoShowAlerta == 0){    
    var i = posicionArrayAlertasGenerales;
    var descripcion = arrayAlertasGenerales[i]['descripcion'];
    var asunto = arrayAlertasGenerales[i]['asunto'];
    var color = arrayAlertasGenerales[i]['color'];
    var titulo = arrayAlertasGenerales[i]['titulo'];
    var confirma = arrayAlertasGenerales[i]['confirma'];
    var idAlerta = arrayAlertasGenerales[i]['id_alerta'];
    if(vieneDeUnCampoAlerta == "1"){
      if(confirma == "Si"){
        $("#row_contra_alerta").show();
        esConfirma =true;
        $("#formAlertaGeneral .modal-footer").show();
      }else{
        esConfirma =false;
        $("#row_contra_alerta").hide();
        $("#formAlertaGeneral .modal-footer").hide();
      }
    }else{
      esConfirma =false;
      $("#row_contra_alerta").hide();
      $("#formAlertaGeneral .modal-footer").hide();
    }
    $("#formAlertaGeneralAux").html(titulo);
    $("#modal-header-formAlertaGeneral").css("background-color", color);
    if(color != ""){      
      $("#formAlertaGeneralAux").css("color", "white");
    }
    document.getElementById("id_alerta_oculto").value =idAlerta;
    CKEDITOR.instances['general_descripcion_alerta'].setData(descripcion);    
    document.getElementById("general_asunto_alerta").value = asunto;
    posicionArrayAlertasGenerales++;
  }
  cantidadVecesLlamadoHideAlerta = 0;
  cantidadVecesLlamadoShowAlerta = 1;
})

function confirmarAlertaCortar(){  
  posicionArrayAlertasGenerales = posicionArrayAlertasGenerales + 10;
  $("#"+$("#idQueAbrePadreAlerta").val()).val("");
  if($("#idOcultoQueAbreAlerta").val() == "idOcultoRelacion"){
    $("."+$("#idOcultoQueAbreAlerta").val()).val("");
  }else{
    $("#"+$("#idOcultoQueAbreAlerta").val()).val("");
  }
  $("#formAlertaGeneral").modal("hide");
}

function confirmarContraAlerta (){
  var base_url = document.getElementById("base_url").value;
  var id_alerta= document.getElementById("id_alerta_oculto").value;
  var contra= document.getElementById("contra_alerta").value;
  var ok= true;
  if (contra ==''){
    marcarError('contra_alerta', 'Campo obligatorio');
    ok=false;
  }
  if (ok){
    $.ajax({
      url:base_url+"Alerta/verificarContrasenaAlerta",
      type: 'POST',     
      data: { id_alerta:id_alerta, contra:contra }
      }).done(function(respuesta){
          if (respuesta == 'OK'){
            esConfirma=false;
            document.getElementById("contra_alerta").value = '';
            $("#formAlertaGeneral").modal("hide");           
          } else {
            marcarError('contra_alerta', 'Contraseña Incorrecta');
          }
      })
  }
  

}