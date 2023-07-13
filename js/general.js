$(function() {
	
	//===== Collapsible plugin for main nav =====//
	if ($('.expand').length) {
		$('.expand').collapsible({
			defaultOpen: 'current,third',
			cookieName: 'navAct',
			cssOpen: 'subOpened',
			cssClose: 'subClosed',
			speed: 200
		});
	}

	//===== Datatables =====//
	if ($('#data-table').length) {

		var texto = $('#data-table tbody tr:eq(0) td:eq(0)').text();

		if(texto!="No existen registros"){

			oTable = $('#data-table').dataTable({
				"bJQueryUI": false,
				"bAutoWidth": false,
				"sPaginationType": "full_numbers",
				"iDisplayLength": 25,
				"bLengthChange": false,
				"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
				"oLanguage": {
					"sSearch": "<span>Filtrar:</span> _INPUT_",
					"sLengthMenu": "<span>Mostrar:</span> _MENU_",
					"sInfo": "Mostrando _START_ de _END_ de _TOTAL_ registros",
					"sInfoFiltered": "(Filtrado de _MAX_ registros)",
					"oPaginate": { "sFirst": "Primero", "sLast": "Ultimo", "sNext": ">", "sPrevious": "<" }
				}
		    });
		}
	}

	if ($('#data-table2').length) {

		var texto = $('#data-table2 tbody tr:eq(0) td:eq(0)').text();

		if(texto!="No existen registros"){

			oTable = $('#data-table2').dataTable({
				"bJQueryUI": false,
				"bAutoWidth": false,
				"sPaginationType": "full_numbers",
				"iDisplayLength": 5,
			    "bLengthChange": false,
			    "bInfo": false,
				"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
				"oLanguage": {
					"sSearch": "<span>Filtrar:</span> _INPUT_",
					"sLengthMenu": "<span>Mostrar:</span> _MENU_",
					"sInfo": "Mostrando _START_ de _END_ de _TOTAL_ registros",
					"sInfoFiltered": "(Filtrado de _MAX_ registros)",
					"oPaginate": { "sFirst": "Primero", "sLast": "Ultimo", "sNext": ">", "sPrevious": "<" }
				}
		    });
		}
	}

	

    //===== Fancybox =====//
	 if ($('.lightbox').length) {	 	
		$(".lightbox").fancybox({
			'padding': 2
		});
	}

	//Accion cambiar de idioma
    $("#btnCambiarIdioma").click(function(e){
    	e.preventDefault();

    	$.post("/inc/cambiarIdioma.php",{},function(data){
    		sucess:
    			window.location.href = window.location.href;		
    	});
    	
    });



    //Accion de Grid
    $(document).on('click', '.accionGrid', function (e) {       
    	e.preventDefault();

    	var title = $(this).attr('title');
    	var valores = title.split(' ');
    	var id = ""; 

    	if (title.indexOf("Estatus") >= 0)
	    	id = $(this).parent().parent().attr('id');
	    else
	    	id = $(this).parent().parent().parent().parent().attr('id');
    	
    	var valores2 = id.split('-');

    	switch(valores[0]){
    		case "Editar":
    			window.location.href = "/"+valores[1]+"/captura.php?evt=U&id="+valores2[1];
    		break;
    		case "Eliminar":
    			if(!confirm("Esta seguro de eliminar el registro actual?")){
                  return false;  
                }
    			window.location.href = "/"+valores[1]+"/eliminar.php?evt=D&id="+valores2[1];
    			break;
    		case "EstatusA":
    			if(!confirm("Esta seguro de Desactivar el registro actual?")){
                  return false;  
                }
    			window.location.href = "/"+valores[1]+"/estatus.php?evt=A&id="+valores2[1];
    		break;
    		case "EstatusI":
    			if(!confirm("Esta seguro de Activar el registro actual?")){
                  return false;  
                }
    			window.location.href = "/"+valores[1]+"/estatus.php?evt=I&id="+valores2[1];
    		break;
    	}


    });



    //Accion de Grid
    $(document).on('click', '.accionDel', function (e) {       
    	//e.preventDefault();

    	if(!confirm("Esta seguro de realizar la Accion actual?")){
                  return false;  
                }

                window.location.href = "/cotizaciones/"+$(this).attr('href');


    });


    $(document).on('click', '.accionGridSel', function (e) {       
    	e.preventDefault();    	
    	
    	var id = $(this).parent().parent().parent().parent().attr('id');
    	
    	var valores2 = id.split('-');

    	if(valores2[1]>0){
    		$("#cproducto_folio").val(valores2[1]);
    			

    		$.post("/inc/obtenerPrecio.php",{'tipo':'producto','folio':valores2[1]},function(data){
    		sucess:
    			$("#concepto").val($("#"+id+" td:eq(1)").text());
    			$("#precio").val(data);		
    		});

    	}

    });

	//Accion de Grid
    $(document).on('click', '.accionGridInt', function (e) {   
    	e.preventDefault();

    	var title = $(this).attr('title');
    	var valores = title.split(' ');
    	var id = ""; 

    	if (title.indexOf("Estatus") >= 0)
	    	id = $(this).parent().parent().attr('id');
	    else
	    	id = $(this).parent().parent().parent().parent().attr('id');
    	
    	var valores2 = id.split('-');

    	switch(valores[0]){
    		case "Editar":

    			if (title.indexOf("empresas") >= 0){
	    			$("#txtFolio").val(valores2[1]);    			


	    			$("#descrip").val($("#"+id+" td:eq(2)").text());
	    			$("#email").val($("#"+id+" td:eq(3)").text());
	    			$("#vistaPrevia img").attr("src",$("#"+id+" td:eq(4) a img").attr("src"));
	    			$("#vistaPrevia").show();
	    		}

	    		
	    		

	    		

    		break;
    	}


    });

    //Esto es para el boton de cancelar del formulario
    $(".accionForm").click(function(){    	
    	
    	var modulo = $("#txtModulo").val();

    	window.location.href = "/"+modulo+"/";


    });

   


	//Validar si escribio algo en el form de Proveedores de Tours
	$("#frmProveedorTour").submit(function(){

		var nombre = $("#nombre").val();
		var apellido = $("#apellido").val();
		var tel = $("#tel").val();
		var email = $("#email").val();
		var cmembresia_folio = $("#cmembresia_folio").val();
		var ccanal_folio = $("#ccanal_folio").val();

		if(nombre==""){
			alert("Por favor ingrese el nombre del Cliente");
			$("#nombre").focus();
			return false;
		}

		if(apellido==""){
			alert("Por favor ingrese el apellido del Cliente");
			$("#apellido").focus();
			return false;
		}

		if(tel==""){
			alert("Por favor ingrese un numero de teléfono del Cliente");
			$("#tel").focus();
			return false;
		}

		if(email==""){
			alert("Por favor ingrese un email del Cliente");
			$("#email").focus();
			return false;
		}

		if(cmembresia_folio=="0"){
			alert("Por favor seleccione una Membresía");
			$("#cmembresia_folio").focus();
			return false;
		}

		if(ccanal_folio=="0"){
			alert("Por favor seleccione un Canal");
			$("#ccanal_folio").focus();
			return false;
		}

		

		return true;
	});

/*
	$("#cproducto_folio").change(function(e){
    	e.preventDefault();

    	var valor = $(this).val();
    	var texto = $('#cproducto_folio option:selected').html();

    	if(valor==0) return false;

    	$.post("/inc/obtenerPrecio.php",{'tipo':'producto','folio':valor},function(data){
    		sucess:
    			$("#precio").val(data);	
    			$("#concepto").val(texto);	
    	});
    	
    });*/

	

	//fin del document
});
