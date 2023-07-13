<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title>Cotizador Login - Printec</title>
<link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
<!--[if IE 8]><link href="/css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->

</head>

<body class="bgLogin">

	<!-- Fixed top -->
	<div id="top">
		<div class="fixed">
			<a href="/" title="" class="logo"><img src="/img/mob-logo.png" width="126" alt="" /></a>			
		</div>
	</div>
	<!-- /fixed top -->


	<!-- Content container -->
	<div id="container">

		<!-- Content -->
		<div id="content">


			<!-- Horizontal form -->
	                    <form id="frmLogin" action="/usuario/procesa.php" class="form-horizontal" method="post">
	                        <div class="widget">
	                            <div class="navbar"><div class="navbar-inner"><h6>Control de Acceso</h6></div></div>
                                <input type="hidden" id="txtIdioma" name="txtIdioma" value="esp" />

	                            <div class="well">
	                            
	                                <div class="control-group">
	                                    <label class="control-label">Usuario</label>
	                                    <div class="controls"><input type="text" id="txtUsu" name="txtUsu" class="span12" placeholder="Usuario" maxlength="20" /></div>
	                                </div>
	                                
	                                <div class="control-group">
	                                    <label class="control-label">Contraseña</label>
	                                    <div class="controls"><input type="password" id="txtPw" name="txtPw" class="span12" placeholder="Contraseña" maxlength="10" /></div>
	                                </div>	
	                                
	                                <div class="form-actions align-right">
	                                    <button type="submit" id="btnIngresar" class="btn btn-primary">Ingresar</button>
	                                </div>

	                            </div>
	                            
	                        </div>
	                    </form>
	                    <!-- /horizontal form -->



		</div>
		<!-- /content -->

	</div>
	<!-- /content container -->


	<!-- Footer -->
	<div id="footer">
		<div class="copyrights">&copy;  Printec <?=date("Y")?></div>		
	</div>
	<!-- /footer -->

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

	<script type="text/javascript" src="/js/files/bootstrap.min.js"></script>

	<script type="text/javascript" src="/js/fcnLogin.js"></script>

</body>
</html>
