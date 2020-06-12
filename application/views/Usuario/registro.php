
<!DOCTYPE html>
<html style="height: 100%">
<head>
  <?php $this->view('menu/css'); ?>
</head>
<body class="hold-transition login-page" style="background-image: url('<?php echo base_url('plugin') ?>/imagenes/fondo.jpg'); background-repeat: no-repeat;background-size: 100% 100%;">

  <div class="login-box" style="margin: 1% auto !important;">
    <div class="login-logo">
      <img width='162px' src="<?php echo base_url('plugin') ?>/imagenes/logo.png">
      <br>
      <b style="text-shadow: 2px 0 0 #fff, -2px 0 0 #fff, 0 2px 0 #fff, 0 -2px 0 #fff, 1px 1px #fff, -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff; font-size:50px">SIMPLAPP</b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body" style="border-radius: 10px;">
        <input type="hidden" name="enviar_form" id="enviar_form" value="1">
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="text" class="form-control campo" name="nombre" id="nombre" placeholder="Nombre Completo" required="required">
          <span class="glyphicon  glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="text" class="form-control campo" name="correo" id="correo" placeholder="Correo" required="required" value="<?php echo $correo; ?>">
          <span class="glyphicon  glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="password" class="form-control campo" id="password" name="password" placeholder="Contraseña" required="required">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="password" class="form-control campo" id="repetir" name="repetir" placeholder="Repetir Contraseña "required="required">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
         <div class="form-group has-feedback">
          <input style="border-radius: 10px;" type="text" class="form-control campo" maxlength="8" id="licencia" name="licencia" placeholder="Licencia" required="required">
          <span class="glyphicon glyphicon-home form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="alert alert-danger" id="error" style="display: none; margin-left: 15px; margin-right: 15px; padding-left: 0px !important; padding-right: 0px !important;">
            <div align='center' id="texto_error"></div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12">
            <button  style="width: 100%" onclick="registro()" id="boton_registro" class="btn btn-primary ">Registrarse</button>
          </div>
        </div>
        <div class="row">
           <div class="col-xs-5">
            <div class="checkbox icheck">
              <a style="cursor:pointer;" href="<?php echo base_url() ?>">
                Volver al Login
              </a>
            </div>
          </div>        
        </div>
    </div>
    
  </div>

<?php $this->view('menu/js'); ?>
  
  
<script>
  $("input").keypress(function(e){
    if(e.keyCode == 13){
      registro();
    }
  });
  function registro(){
      $('#boton_registro').prop('disabled', true);
      let nombre = $('#nombre').val();
      let correo = $('#correo').val();
      let password = $('#password').val();
      let repetir = $('#repetir').val();
      let licencia = $('#licencia').val();
      let error ='';
      let ok = true; 
      if (password != repetir){
          error = 'Las contraseñas no coinciden';  
          ok=false;
      } else {
        if (password.length < 8) {
          ok=false;
          error="La contraseña debe tener minimo 8 caracteres";
        }
        if (!password.match(/[A-z]/) || !password.match(/[0-9]/)) {
          ok=false;   
          error="La contraseña debe tener al menos un numero y una letra";
        }
      }
      if(correo != ''){
        if(!validoEmail(correo)){
          ok=false;   
          error="La contraseña debe tener al menos un numero y una letra";
        }
      }
      if (correo =='' || password == '' || nombre == ''|| licencia == ''|| repetir == ''){
        error = 'Complete todos los campos';  
        ok=false;
      }
      if (ok){
        $.ajax({
          url:'<?php echo base_url() ?>usuario/registro_bd_alta',
          type: 'POST',
          dataType: "json",
          data: {nombre, correo, password, repetir, licencia},
        }).done(function(respuesta){
          if (respuesta['mensaje'] == 'OK'){
            $("#error").removeClass('alert-danger').addClass('alert-success'); 
            $("#error").css('display', 'block');   
            $("#texto_error").text('Se le ha enviado un correo para verificar su cuenta');  
          } else {
            $("#error").removeClass('alert-success').addClass('alert-danger');  
            $("#error").css('display', 'block');  
            $("#texto_error").text(respuesta['error']);  
          }
          $('#boton_registro').prop('disabled', false);
        })  
      } else {
        $("#error").removeClass('alert-success').addClass('alert-danger');  
        $("#error").css('display', 'block');  
        $("#texto_error").text(error);  
        $('#boton_registro').prop('disabled', false);
      }
    }
</script>
</body>
</html>
