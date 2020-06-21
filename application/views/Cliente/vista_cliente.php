<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php $this->load->view('menu/css') ?>
  </head>
  <body> 
    <br>
    <div class="container">
    <div class="row">
    <?php 
    $this->load->view('cliente/comprobantes_pendientes');
    $this->load->view('menu/js.php');
    ?>
    </div>
    </div>
  </body>  
</html>
