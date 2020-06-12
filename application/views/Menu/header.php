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
$cantidad_alertas = count($alertas);
?>
<!DOCTYPE html>
<html>
	<head>		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php if ($cantidad_alertas > 0) { echo "(".$cantidad_alertas.") "; } ?> Simplapp</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<?php $this->load->view('menu/css') ?>
	</head>
	<body class="hold-transition skin-<?php echo $menu_color; ?> sidebar-mini fixed <?php echo $menuFijoClase; ?>">
		<div class="wrapper">
			<header class="main-header">
				<a href="<?php echo base_url() ?>tablero" class="logo">
					<span style="font-family:Lato:400,900 !important;" class="logo-mini"><b><span><img width='70%' src="<?php echo base_url('plugin') ?>/imagenes/logo.png"></span></b></span>
					<span style="font-family:Lato:400,900 !important;" class="logo-lg"><b><span>SIMPLAPP</span></b></span>
				</a>
				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							<li class="dropdown notifications-menu">
								<a href="<?php echo base_url() ?>alertas" title="Alertas">
									<i class="fa fa-bell"></i>
									<?php
									if($cantidad_alertas > 0){ 
									?>
									<span class="label label-warning" ><?php echo $cantidad_alertas ?></span>
									<?php } ?>
								</a>								
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
											<?php echo $this->session->userdata('empresa') ?>
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
							<input type="radio" class="radio-button minimal" id="menuFijo" <?php echo $menuFijoRadioCheck; ?>> <label for="menuFijo" style="font-weight: normal;"> Menu Fijo <label>
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
			                <a href="<?php echo base_url() ?>licencias"><i class="fa fa-key"></i> <span>Licencias</span></a>
			            </li>
			        	<?php } ?>						
					</ul>
				</section>
			</aside>
			<div class="modal fade" id="modalContra" tabindex="-1" role="dialog"  aria-hidden="true">
				<div class="modal-dialog modal-xs">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="tituloContra">Cambiar Contraseña</h4>
						</div>
						<div class="modal-body">	
						 	<div class="text-right">
								<a class="btn btn-primary btn-form" onclick="cambiarContrasena()">Guardar</a>
								<a class="btn btn-danger btn-form" data-dismiss="modal">Cancelar</a>
							</div>			
							<div class="row">
								<div class="col-md-12">
									<label class="lab">Contraseña Actual</label>
									<input type="password" data-tipo="string" class="form-control" id="contraActual">
									<div class="error_color" id="error_contraActual"></div>
								</div>
								<div class="col-md-12">
									<label class="lab">Nueva Contraseña</label>
									<input type="password" data-tipo="string" class="form-control" id="nuevaContra">
									<div class="error_color" id="error_nuevaContra"></div>
								</div>
								<div class="col-md-12">
									<label class="lab">Confirmar Contraseña</label>
									<input type="password"  data-tipo="string" class="form-control" id="confirmarContra">
									<div class="error_color" id="error_confirmarContra"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="hidden-xs hidden-sm">
				<span class='irArriba'> 
					<center>
						<span class="glyphicon glyphicon-chevron-up"></span>	
					</center>
				</span>	
			</div>

			<input type="hidden" id="Fecha_Actual" value="<?php $fecha=getdate(); if(strlen($fecha['mon'])==1) $fechaMes = '0'.$fecha['mon']; else $fechaMes = $fecha['mon']; if(strlen($fecha['mday'])==1) $fechaDia = '0'.$fecha['mday']; else $fechaDia = $fecha['mday']; echo $fechaDia .'/'. $fechaMes .'/'. $fecha['year'];?>">
			<input type="hidden" id="personalizarElegidos" value="0">			
			<input type="hidden" id="base_url" value="<?php echo htmlspecialchars(base_url()); ?>">
			<input type="hidden" id="current_url_hidden" value= "<?php echo $current_url; ?>">
		
			<?php $this->load->view('menu/js.php') ?>
			<script>				
				$(document).on('click',".content-wrapper",(function(){
					if($("#menuFijoHidden").val() == "No"){						
						$('.fixed').addClass('sidebar-collapse');
					}
				}));

				$(".main-sidebar").click(function(){$('.fixed').removeClass('sidebar-collapse'); });

				$('.radio-button').on("click", function(event){
					if($(this).val() == "Si"){
						var menuFijo = "No";
						$(this).val(menuFijo);
						$("#menuFijoHidden").val(menuFijo);
						$(this).prop('checked', false);			
						$("body").removeClass("sidebar-collapse");			
					}else if($(this).val() == "No"){
						var menuFijo = "Si";
						$(this).val(menuFijo);
						$("#menuFijoHidden").val(menuFijo);
						$(this).prop('checked', true);
						$("body").addClass("sidebar-collapse");
					}
					cambiar_menu('menu_fijo', menuFijo);
				});	

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
					let error= '';
					if(nuevaContra == confirmarContra){
						if (nuevaContra.length < 8 ) {
				          ok=false;
				          error="La contraseña debe tener minimo 8 caracteres";
				        }
				        if (!nuevaContra.match(/[A-z]/) || !nuevaContra.match(/[0-9]/)) {
				          ok=false;   
				          error="La contraseña debe tener al menos un numero y una letra";
				        }
					}else{
						ok =false;
						error= "Las contraseñas no coinciden";
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

				$(".int").keypress(function(e){
					var key = e.charCode;
					console.log(key);
				    return key >= 48 && key <= 57;
				});

				$(".int").blur(function(){
					var valor = this.value;
					if (!Number.isInteger(parseInt(valor))){
						$(this).val("");
					}
				});
				
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
			</script>
	</body>
</html>
