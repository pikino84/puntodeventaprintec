<?php
	error_reporting(0);
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/funciones.php');
	restrinct();

	require_once($_SERVER['DOCUMENT_ROOT'].'/inc/configuracion.inc.php');
	$link = conectar($bd_host, $bd_usuario, $bd_pwd, $bd_nombre);
	

	$ln = ln();

	$descLn = (($ln=='esp') ? 'Español' : 'Ingles');
    
    $pagTitle = "Cotizador - Printec";

    $iduser = (string)$_SESSION["rtLogin"];

    $listaMembresia = array("1"=>"Publico General","3"=>"Distribuidor");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title><?=$pagTitle?></title>
<link href="/css/bootstrap.css?v=3" rel="stylesheet" type="text/css" />



<!--[if IE 8]><link href="/backend/css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->

<?php
	if(isset($listaCSS)){

		foreach ($listaCSS as $keyCSS => $valueCSS) {
			?>				
				<link href="<?=$valueCSS?>" rel="stylesheet" type="text/css" />
			<?php
		}

	}
?>

</head>

<body>

	<!-- Fixed top -->
	<div id="top">
		<div class="fixed">
			<a href="/" title="" class="logo"><img src="/img/<?=(string)$_SESSION["rtLogo"]?>" height="126" alt="" /></a>
			<ul class="top-menu">				
				<li class="dropdown">
					<a class="user-menu" data-toggle="dropdown"><img src="/img/userpic.png" alt="" /><span><?=getNombreUsuario($link)?> <b class="caret"></b></span></a>
					<ul class="dropdown-menu">
						<!--<li><a href="#" title=""><i class="icon-user"></i>Perfil</a></li>-->
						<?php if($_SESSION['rtSuperAdmin']==1){ ?>
						<li><a href="/inc/cambiarmembresia.php" title=""><i class="icon-remove"></i>Cambiar membresía</a></li>
						<?php }?>
						<li><a href="/cambiarpw/captura.php?evt=I&id=0" title=""><i class="icon-remove"></i>Cambiar contraseña</a></li>
						<li><a href="/usuario/salir.php" title=""><i class="icon-remove"></i>Salir</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
	<!-- /fixed top -->


	<!-- Content container -->
	<div id="container">

		<!-- Sidebar -->
		<div id="sidebar">

			<div class="sidebar-tabs">
		       

		        <div id="general">			        

				    <!-- Main navigation -->
			        <ul class="navigation widget">
			            <li class="active"><a href="/" title=""><i class="icon-home"></i>Menu <?php echo "[".$listaMembresia[$_SESSION['rtMembresia']]."]";?></a></li>

			            <?php if($_SESSION['rtSuperAdmin']==1){ ?>
			            <li><a href="#" title="" class="expand"><i class="icon-reorder"></i>Configuración<strong>3</strong></a>
			                <ul>
			                	<li><a href="/empresas/" title="">Empresas</a></li>
			                    <li><a href="/sucursales/" title="">Sucursales</a></li>
			                    <li><a href="/vendedores/" title="">Vendedores</a></li>		                    
			                </ul>
			            </li>
			             <?php } elseif($_SESSION['rtAdmin']==1) {?>

			             	<li><a href="#" title="" class="expand"><i class="icon-reorder"></i>Configuración<strong>3</strong></a>
			                <ul>
			                    <li><a href="/vendedores/" title="">Vendedores</a></li>		                    
			                </ul>
			            </li>

			             <?php }?> 

			            
			            <li><a href="#" title="" class="expand"><i class="icon-reorder"></i>Catalogos<strong><?php if($_SESSION['rtSuperAdmin']==1){ ?>8<?php }else{?>5<?php }?></strong></a>
			                <ul>
			                	<li><a href="/corporativos/" title="">Corporativos</a></li>
			                    <li><a href="/clientes/" title="">Clientes</a></li>
			                    <li><a href="/canales/" title="">Canal</a></li>
			                    <li><a href="/tiposdeimpresion/" title="">Tipos de Impresión</a></li>
			                    <li><a href="/tinta/" title="">Tinta</a></li>
			                    <?php if($_SESSION['rtSuperAdmin']==1){ ?>
			                    	<li><a href="/rangos/" title="">Rangos</a></li>
			                    	<li><a href="/tarifasporrango/" title="">Tarifas por rango</a></li>	
			                    	<li><a href="/serviciosespeciales/" title="">Servicios Especiales</a></li>	
			                    
			                    <?php } elseif($_SESSION['rtAplicaSE']==1) {?>

						             	<li><a href="/serviciosespeciales/" title="">Servicios Especiales</a></li>	

					             <?php }?>          
			                    
			                </ul>
			            </li>
			            <li><a href="#" title="" class="expand"><i class="icon-reorder"></i>Cotizaciones<strong>4</strong></a>
			                <ul>
			                	<li><a href="/productos/" title="">Crear cotizacion</a></li>	 
			                	<li><a href="/cotizaciones/" title="">Cotizaciones Autorizadas</a></li>
			                	<li><a href="/cotizaciones/pendientes.php" title="">Cotizaciones Pendientes</a></li> 
			                	<li><a href="/cotizaciones/rechazadas.php" title="">Cotizaciones Rechazadas</a></li>
			                </ul>
			            </li>
			            <?php if($_SESSION['rtSuperAdmin']==1){ ?>
			            <li><a href="#" title="" class="expand"><i class="icon-reorder"></i>Reportes<strong>1</strong></a>
			                <ul>			                	
								<li><a href="/reportes/venta-diaria.php" title="">Reporte de Ventas</a></li>
			                </ul>
			            </li>
			        <?php } ?>
			            
			            
			        </ul>
			        <!-- /main navigation -->

		        </div>

		    </div>
		</div>
		<!-- /sidebar -->


		<!-- Content -->
		<div id="content">

		    <!-- Content wrapper -->
		    <div class="wrapper">

			    <!-- Breadcrumbs line -->
			    <div class="crumbs">
		            <ul id="breadcrumbs" class="breadcrumb"> 
		                <li><a href="/">Inicio</a></li>
		                <?php 
		                if(isset($urlBreadCrumbs)){
		                ?>
		                <?=$urlBreadCrumbs?>
		                <?php 
		                }
		                ?>
		            </ul>
			    </div>
			    <!-- /breadcrumbs line -->