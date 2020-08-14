<?php 
$_SERVER['REQUEST_URI'] = strtolower($_SERVER['REQUEST_URI']);
$request_uri = $_SERVER['REQUEST_URI'];
$foto = base_url('plugin')."/dist2/img/user1.png";
$current_url = current_url();
$id_carpeta = $this->session->userdata('id_carpeta');
$menu_color = $this->session->userdata("menu_color");
if($menu_color == ""){
	$menu_color = "blue";
}
$menuFijo = $this->session->userdata('menu_fijo');
$menuFijoClase = "sidebar-collapse";
$menuFijoRadioCheck = "value='No'";
if($menuFijo == "Si"){
	$menuFijoClase = "";
	$menuFijoRadioCheck = "value='Si' checked";
}

$fecha = getdate(); 
$fechaAno = $fecha['year'];
if(strlen($fecha['mon'])==1){
	$fechaMes = '0'.$fecha['mon']; 
}else{
	$fechaMes = $fecha['mon'];
}
if(strlen($fecha['mday'])==1){
	$fechaDia = '0'.$fecha['mday']; 
}else{
	$fechaDia = $fecha['mday']; 
}
if(strlen($fecha['hours'])==1){
	$fechaHoras = '0'.$fecha['hours']; 
}else{
	$fechaHoras = $fecha['hours']; 
} 
if(strlen($fecha['minutes'])==1){
	$fechaMinutos = '0'.$fecha['minutes']; 
}else{
	$fechaMinutos = $fecha['minutes']; 
}

$cantidad_actividades = count($actividades_no_leidas);

$EmpresasGenerales='';
$posicion = 0;
$empresa_visible_small = '';
foreach  ($empresas as $fila){  
	$EmpresasGenerales .=
	"<div style='border-bottom: 0.9px solid #eee'> 
        <li style='margin:10px;'>		                      
          	<a class='fila-notificacion' onclick='cambiarEmpresa(\"".$fila['id_empresa']."\", \"".$fila['empresa']."\")' data-empresa='' title='Cambiar Empresa'> 
           		<i class='glyphicon glyphicon-home'></i>&nbsp;&nbsp;&nbsp;".$fila['empresa']."
          	</a>
        </li>
	</div>";
	$posicion++;
}
?>
<!DOCTYPE html>
<html>
	<head>		
		<style type="text/css">
			table tr td {
				text-align: center;
				vertical-align: middle;
			}
			table tr th {
				text-align: center;
				vertical-align: middle;
			}
		</style>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php if ($cantidad_actividades > 0) { echo "(".$cantidad_actividades.") "; } ?> PrediCob</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<?php $this->load->view('menu/css') ?>
	</head>
	<body id="menuBody" class="hold-transition skin-<?php echo $menu_color; ?> sidebar-mini fixed <?php echo $menuFijoClase; ?>">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?php echo base_url() ?>tablero" class="logo">
					<span style="font-family:Lato:400,900 !important;" class="logo-mini"><b><span><img width='70%' src="<?php echo base_url('plugin') ?>/imagenes/logo.png"></span></b></span>
					<span style="font-family:Lato:400,900 !important;" class="logo-lg"><b><span>PrediCob</span></b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<ul class="nav navbar-nav hidden-sm hidden-xs">
						<li><a id="empresa_crm_header"><?php echo $this->session->userdata('empresa') ?></a></li>
					<?php 
					if (count($empresas) > 1){ ?>
						<li id="empresasUl" title="Cambiar empresa" class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="glyphicon glyphicon-home"></i>
							</a>		            
					        <ul class="dropdown-menu" style="width: auto !important;">
					            <li>
					              <ul class="menu">
					                <?php 
					                echo $EmpresasGenerales;
					                ?>
					              </ul>
					            </li>
					        </ul>
					    </li>
					<?php 
						$empresa_visible_small = '
						<li id="empresasUl" title="Cambiar empresa" class="dropdown notifications-menu visible-xs visible-sm">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="glyphicon glyphicon-home"></i>
							</a>
					        <ul class="dropdown-menu">
					            <li>
					              <ul class="menu">					                    
					                <div style="border-bottom: 0.9px solid #eee"> 
										<li style="margin:10px;">
											<a class="fila-notificacion" style="color:green !important;" title="Empresa Seleccionada">
												<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp; '.$this->session->userdata('empresa').'
											</a>
						 				</li>
									</div>'.$EmpresasGenerales.'
					              </ul>
					            </li>
					        </ul>
					    </li>';
					} ?>
					</ul>

					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li class="dropdown notifications-menu">
								<a <?php if($cantidad_actividades > 0){ ?>
										class="dropdown-toggle" data-toggle="dropdown"
									<?php }else{ ?>
										href="<?php echo base_url() ?>actividades"
									<?php } ?>>
									<i class="fa fa-bell"></i>
									<?php
									if($cantidad_actividades > 0){ 
									?>
									<span class="label label-warning cantidad_actividades"><?php echo $cantidad_actividades ?></span>
									<?php } ?>
								</a>
								<ul class="dropdown-menu">
					                <li class="header">
					                	<a class="header-notificacion" onclick="vaciarNotificaciones()"><center>Marcar todas como leidas</center></a>
					                </li>
					                <li>
						                <ul class="menu">
						                    <?php foreach($actividades_no_leidas as $fila){ ?>
						                    <div style="border-bottom: 0.9px solid #eee"> 
							                    <li style="margin:10px;">		                      
							                      	<a class="fila-notificacion" href="<?php echo base_url() ?>modificar-actividad?id=<?php echo $fila['id_actividad']; ?>">
							                       		<i class="fa fa-bell"></i> <?php echo $fila['asunto']; ?>
							                      	</a>
							                    </li>
						                	</div>
						                    <?php } ?>
						                </ul>
					                </li>
					                <li class="header">
					                	<a class="footer-notificacion" href="<?php echo base_url(); ?>actividades"><center>Ver todas</center></a>
					                </li>
					            </ul>								
							</li>
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="<?php echo $foto ?>" class="user-image" alt="User Image">
									<span class="hidden-xs"><?php echo $this->session->userdata('nombre') ?></span>
								</a>
								<ul class="dropdown-menu">
									<li class="user-header">
										<img src="<?php echo $foto ?>" class="img-circle" alt="User Image">
										<p>
											<?php echo $this->session->userdata('nombre') ?>
											<small><i class="fa fa-circle text-success"></i> Usuario Conectado</small>
										</p>
									</li>
									<li class="user-footer">
										<div class="pull-left">
						                	<a onclick="cambiarContrasenaModal()" class="btn btn-default btn-flat">Cambiar clave</a>
						                </div>
						                <div class="pull-right">
											<a href="<?php echo base_url() ?>usuario/cerrar_sesion" title="Salir" class="btn btn-default btn-flat">Cerrar Sesi&oacute;n</a>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<a href="<?php echo base_url() ?>configuracion" title="Configuracion"><i class="fa fa-gears"></i></a>
							</li>
						</ul>
					</div>		  
				</nav>		
			</header>

			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image">
							<img src="<?php echo $foto ?>" class="img-circle" alt="User Image">
						</div>
						<div class="pull-left info">
							<p>Usuario</p>
							<a href="#"><i class="fa fa-circle text-success"></i> En Linea</a>
							<input type="hidden" id="menuFijoHidden" value="<?php echo $menuFijo; ?>">
						</div>
					</div>
					<div class="sidebar-form">
						<div class="input-group">
							<input type="text" id="search-input" class="form-control" placeholder="Buscar...">
							<span class="input-group-btn">
								<span name="search" style="cursor:pointer;" class="btn btn-flat">
									<i class="fa fa-search"></i>
								</span>
							</span>
						</div>
					</div>
					<ul class="sidebar-menu" data-widget="tree">
						<li class="header">MENU</li>
						<!--FUNCION EN HELPER QUE GENERA TODAS LAS CARPETAS-->
						<?php
						echo llenarTreeview($carpetas);
						?>

						<!--CLIENTE-->
			            <li>
			                <a href="<?php echo base_url() ?>clientes"><i class="fa fa-users"></i> <span>Clientes</span></a>
			            </li>

			            <!--SEGUIMIENTO-->
			            <li>
			                <a href="<?php echo base_url() ?>seguimiento"><i class="fa fa-book"></i> <span>Seguimiento</span></a>
			            </li>

			            <!--PLANTILLAS-->
			            <li>
			                <a href="<?php echo base_url() ?>plantillas"><i class="fa fa-envelope"></i> <span>Plantillas de Mails</span></a>
			            </li>
						
						<!--REGLA DE NEGOCIO-->
						<li>
			                <a href="<?php echo base_url() ?>reglas"><i class="fa fa-handshake-o"></i> <span>Reglas de negocio</span></a>
			            </li>

			            <!--INTELIGENCIA ARTIFICIAL-->
			            <li>
			                <a href="#"><i class="fa fa-microchip"></i> <span>Algoritmos</span></a>
			            </li>

			            <?php if ($this->session->userdata('permiso') == 'administrador'){ ?>
			            <li>
			                <a href="<?php echo base_url() ?>usuarios"><i class="fa fa-user"></i> <span>Usuarios</span></a>
			            </li>
			        	<?php } ?>

			            <?php if ($this->session->userdata('permiso') == 'licencia'){ ?>
			            <li>
			                <a href="<?php echo base_url() ?>licencias"><i class="fa fa-key"></i> <span>Licencias</span></a>
			            </li>
			        	<?php } ?>
					</ul>
				</section>
			</aside>

			<div class="hidden-xs hidden-sm">
				<span class='irArriba'> 
					<center>
						<span class="glyphicon glyphicon-chevron-up"></span>	
					</center>
				</span>	
			</div>

			<input type="hidden" id="Fecha_Actual" value="<?php echo $fechaDia .'/'. $fechaMes .'/'. $fechaAno; ?>">
			<input type="hidden" id="personalizarElegidos" value="0">			
			<input type="hidden" id="base_url" value="<?php echo htmlspecialchars(base_url()); ?>">
			<input type="hidden" id="current_url_hidden" value= "<?php echo $current_url; ?>">
		
			<?php 
			$this->load->view('menu/js.php') ?>
			<script>				
				$(document).on('click',".content-wrapper",(function(){
					if($("#menuFijoHidden").val() == "No"){						
						$('.fixed').addClass('sidebar-collapse');
					}
				}));

				$(".main-sidebar").click(function(){$('.fixed').removeClass('sidebar-collapse'); });

				function cambiar_menu(columna, valor){
					$.ajax({
						url:"<?php echo base_url() ?>configuracion/cambiar_menu",
						type:"POST",
						data:{columna, valor},
						success: function(){}
					});
				}

		        $(function () {
		            $('#sidebar-form').on('submit', function (e) {
		                e.preventDefault();
		            });

		            $('.sidebar-menu li.active').data('lte.pushmenu.active', true);

		            $('#search-input').on('keyup', function () {
		                var term = $('#search-input').val().trim();

		                if (term.length === 0) {
		                    $('.sidebar-menu li').each(function () {
		                        $(this).show(0);
		                        $(this).removeClass('active');
		                        if ($(this).data('lte.pushmenu.active')) {
		                            $(this).addClass('active');
		                        }
		                    });
		                    return;
		                }

		                $('.sidebar-menu li').each(function () {
		                    if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
		                        $(this).hide(0);
		                        $(this).removeClass('pushmenu-search-found', false);

		                        if ($(this).is('.treeview')) {
		                            $(this).removeClass('active');
		                        }
		                    } else {
		                        $(this).show(0);
		                        $(this).addClass('pushmenu-search-found');

		                        if ($(this).is('.treeview')) {
		                            $(this).addClass('active');
		                        }

		                        var parent = $(this).parents('li').first();
		                        if (parent.is('.treeview')) {
		                            parent.show(0);
		                        }
		                    }

		                    if ($(this).is('.header')) {
		                        $(this).show();
		                    }
		                });

		                $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
		                    $(this).find('.pushmenu-search-found').show(0);
		                });
		            });
		        });

		        $(document).on('click', '.a_treeview:not(.pull-right-container)', function(e){
		        	var html = $(e.target).html();
		        	var bandera = false;
		        	if(html.indexOf("fa fa-angle-left pull-right") > -1){
		        		if(html.indexOf("span_treeview") > -1){
		        			bandera = true;
		        		}
		        	}else if(html != ""){
		        		bandera = true;
		        	}

		        	if(bandera){
		        		var id = $(this).data("id");
		        		location.href = "<?php echo base_url() ?>tablero?id="+id;
		        	}
			    });

				$(document).ready(function(){
					$('.irArriba').click(function(){
						$('body, html').animate({scrollTop: '0px'},300);
					});
				  	$(window).scroll(function(){
				    	if($(this).scrollTop() > 0){
				        	$('.irArriba').slideDown(300);
				        	$('.fijo').addClass('fijo_scroll');
				      	}else{
				       		$('.irArriba').slideUp(300);
				        	$('.fijo').removeClass('fijo_scroll');
				    	}
			  		});
				});

				function marcarError(id, mensaje){
					$("#"+id).css({"border":"1px solid red"});
					if ($("#error_"+id).length > 0){
						document.getElementById("error_"+id).innerHTML =mensaje;
					}
				}
				
				function quitarError(id){
					$("#"+id).css({"border":"1px solid #d2d6de"});
					if ($("#error_"+id).length > 0){
						document.getElementById("error_"+id).innerHTML ="";
					}
				}

				function cambiarContrasenaModal(){
					$('#contraActual').val('');
					$('#nuevaContra').val('');
					$('#confirmarContra').val('');
					$('#modalContra').modal('show');
				}

				function cambiarContrasena(){
					var contraActual = $('#contraActual').val();
					var nuevaContra = $('#nuevaContra').val();
					var confirmarContra = $('#confirmarContra').val();
					let ok = true;
					let error = '';
					if(nuevaContra == confirmarContra){
						if (nuevaContra.length < 8) {
				          ok = false;
				          error = "La contraseña debe tener minimo 8 caracteres";
				        }
				        if (!nuevaContra.match(/[A-z]/) || !nuevaContra.match(/[0-9]/)) {
				          ok = false;   
				          error = "La contraseña debe tener al menos un numero y una letra";
				        }
					}else{
						ok = false;
						error = "Las contraseñas no coinciden";
					}
					if (ok){
						$.ajax({
							url:"<?php echo base_url() ?>usuario/cambiarContrasena",
							type:"POST",
							data:{nuevaContra, contraActual},
							success: function(respuesta){
								if(respuesta == "OK"){
									alert("Se ha cambiado con exito");
									$('#modalContra').modal('hide');
								}else{
									alert(respuesta);
								}
							}
						});	
					} else {
						marcarError("nuevaContra", error);
						marcarError("confirmarContra", error);
					}
				}
				
				/*abrir los acordeones*/
				function cambiar_check(id){
					var checked = $("#item"+id).is(":checked");
					if(!checked){
						$("#icon"+id).removeClass("glyphicon-chevron-down");
						$("#icon"+id).addClass("glyphicon-chevron-left");
					}else{
						$("#icon"+id).addClass("glyphicon-chevron-down");
						$("#icon"+id).removeClass("glyphicon-chevron-left");
					}

					$("#item"+id).prop("checked", checked);
				}	

				
				$('.sidebar').mouseover(function() {
					$('#menuBody').removeClass('sidebar-collapse');
				}).mouseout(function() {
					if (!$('#menuFijo').is(':checked')){
						$('#menuBody').addClass('sidebar-collapse');
					}
				});

				function cambiarEmpresa(id_empresa, empresa){
					$.ajax({
				      url:'<?php echo base_url() ?>usuario/empresas',
				      type: 'POST',
				      dataType: "json",
				      data: {id_empresa, empresa},
				    }).done(function(respuesta){
				      	if (respuesta['mensaje'] == 'OK'){
				        	window.location = "<?php echo base_url() ?>tablero";
				    	}
				    });
				}

				function vaciarNotificaciones(){
					$.ajax({
				      url:'<?php echo base_url() ?>configuracion/vaciarNotificaciones',
				      type: 'POST',
				      data: {},
				    }).done(function(respuesta){
				      	if (respuesta == 'OK'){
					        document.title = "PrediCob";
					        $('.cantidad_actividades').hide();
					    }
				    });
				}
			</script>
	</body>
</html>
