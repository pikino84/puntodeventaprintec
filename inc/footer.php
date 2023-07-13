<!-- Footer -->
	<div id="footer">
		<div class="copyrights">&copy;  Printec <?=date("Y")?></div>		
	</div>
	<!-- /footer -->

	<script type="text/javascript" src="/js/jquery.min.js?v=1"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.10.4.custom.js"></script>

	

	<script type="text/javascript" src="/js/files/bootstrap.min.js?v=1"></script>

	<script type="text/javascript" src="/js/plugins/ui/jquery.collapsible.min.js"></script>

	<?php
		if(isset($listaJS)){

			foreach ($listaJS as $keyJS => $valueJS) {
				?>
					<script type="text/javascript" src="<?=$valueJS?>?v=1"></script>
				<?php
			}

		}
	?>

	<script type="text/javascript" src="/js/general.js?v=21"></script>

</body>
</html>