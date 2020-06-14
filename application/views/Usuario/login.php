<!DOCTYPE html>
<html style="height: 100%">
<head>
  <?php $this->view('menu/css'); ?>
  <style type="text/css">
  
  </style>

</head>
<body class="hold-transition login-page" style="background-image: url('<?php echo base_url('plugin') ?>/imagenes/fondo.jpg'); background-repeat: no-repeat;background-size: 100% 100%;">
  <div class="login-box" style="margin: 1% auto !important;">
    <div class="login-logo">
      <img width='162px' src="<?php echo base_url('plugin') ?>/imagenes/logo.png">
      <br>
      <b style="text-shadow: 2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff; font-size:50px">SIMPLAPP</b>
    </div>
    <div class="login-box-body" style="border-radius: 10px;">
        <input type="hidden" name="enviar_form" id="enviar_form" value="1">
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="text" class="form-control" name="correo" id="correo" placeholder="Correo" required="required">
          <span class="glyphicon  glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required="required">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="alert alert-danger" id="error" style="display: none; margin-left: 15px; margin-right: 15px; padding-left: 0px !important; padding-right: 0px !important;">
            <div align='center' id="texto_error"></div>
          </div>
        </div>
        <?php if ($this->session->flashdata('error-alerta')) {?>
        <div class="row">
            <div class="alert <?php echo $this->session->flashdata('color') ?>" id="errorConfirmacion" style="display: none; margin-left: 15px; margin-right: 15px; padding-left: 0px !important; padding-right: 0px !important;">
            <div align='center' id="texto_error"><?php echo $this->session->flashdata('error-alerta') ?></div>
           </div>
        </div>
      <?php } ?>
        <div class="row">
          <div class="col-xs-12">
            <button style="width: 100%" onclick="login()" class="btn btn-primary">Ingresar</button>
          </div>
        </div>
         <div class="row">
           <div class="col-xs-5">
            <div class="checkbox icheck">
              <a style="cursor:pointer;" href="<?php echo base_url() ?>registro">
                Registrarse
              </a>
            </div>
          </div>
          <div class="col-xs-7" style="padding-left: 0px !important;">
            <div class="checkbox icheck pull-right">
              <a style="cursor:pointer;" onclick="modalRecupero()">
                ¿Has olvidado la contraseña?
              </a>
            </div>
          </div>         
        </div>
    </div>
  </div>


 <div id="modalEnviarMail" class="modal fade" role="dialog">  
    <div class="modal-dialog modal-xs">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Recupero de contraseña</h4>
        </div>
        <div class="modal-body">
           <div class='row' >
            <div class='col-sm-12' >
              <div class="form-group">
                <p>Ingrese la casilla de correo asociado a su cuenta. Se le enviara un enlace para que pueda iniciar el proceso de cambio de contraseña:</p>
              </div>
            </div>
          </div>
          <div class='row' >
            <div class='col-sm-12' >
              <div class="form-group">
                <label for="mail">Correo</label>
                <input type="text" class="form-control" style="border-radius: 10px;" id="correoModal" placeholder="Correo">
              </div>
            </div>
          </div>
          <div class="row">
          <div class="alert alert-danger" id="error_modal" style="display: none; margin-left: 15px; margin-right: 15px; padding-left: 0px !important; padding-right: 0px !important;">
            <div align='center' id="texto_error_modal"></div>
          </div>
        </div>
        </div>
         <div class="modal-footer">
          <button class="btn btn-primary" onclick="enviarMail()">Enviar Mail</button>
          <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>

  <?php $this->view('menu/js'); ?>
  
  <script>
  $(document).ready(function(){
    $("#correo").focus();
  })
  $("#password, #correo").keypress(function(e){
    if(e.keyCode == 13){
      login();
    }
  });
  $("#correoModal").keypress(function(e){
    if(e.keyCode == 13){
      enviarMail();
    }
  });      
  function login(){
    let correo = $('#correo').val();
    let password = $('#password').val();
    if (correo == '' || password == ''){
      $("#error").css('display', 'block'); 
      $("#texto_error").text('Complete todos los campos');
    }else if(!validoEmail(correo)){
      $("#error").css('display', 'block'); 
      $("#texto_error").text('Correo no tiene el formato correcto');
    } else {
      $.ajax({
        url:'<?php echo base_url() ?>usuario/login',
        type: 'POST',
        dataType: "json",
        data: {correo, password},
      }).done(function(respuesta){
        if (respuesta['mensaje'] == 'OK'){
          window.location = "<?php echo base_url() ?>tablero";
        } else {
          $("#error").css('display', 'block');  
          $("#texto_error").text(respuesta['error']);  
        }
      })  
    }
  }

  function modalRecupero(){
    $('#modalEnviarMail').modal('show');
  }

  function enviarMail(){
    var mail = $("#correoModal").val();
    if (mail != ''){
      if(validoEmail(mail)){
        $.ajax({
            url:'<?php echo base_url() ?>usuario/enviarMailRecupero',
            type: 'POST',
          data: {mail},
        }).done(function(respuesta){
          if (respuesta == 'OK'){
            $("#error_modal").removeClass('alert-danger').addClass('alert-success');  
            $("#error_modal").css('display', 'block');  
            $("#texto_error_modal").text('Se ha enviado un correo a su casilla para restablecer la contraseña');          
          } else {
            $("#error_modal").removeClass('alert-success').addClass('alert-danger');  
            $("#error_modal").css('display', 'block');  
            $("#texto_error_modal").html('No existe cuenta asociada al correo ingresado. <a href="<?php echo base_url() ?>registro?co='+mail+'">Registrece</a>');
          }
        })
      }else{
        $("#error_modal").removeClass('alert-success').addClass('alert-danger');  
        $("#error_modal").css('display', 'block');
        $("#texto_error_modal").text('Correo no tiene el formato correcto');
      }
    } else {
      $("#error_modal").removeClass('alert-success').addClass('alert-danger');  
      $("#error_modal").css('display', 'block');  
      $("#texto_error_modal").text('Complete el campo');
    }
  }
  </script>
</body>
</html>
