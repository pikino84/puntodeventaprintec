$(function(){

	//Validar si escribio algo en el form
	$("#frmLogin").submit(function(){

		var txtUsu = $("#txtUsu").val();
		var txtPw = $("#txtPw").val();

		if(txtUsu==""){
			alert("Por favor ingrese su usuario...");
			return false;
		}

		if(txtPw==""){
			alert("Por favor ingrese su contraseña...");
			return false;
		}

		return true;


	});


});