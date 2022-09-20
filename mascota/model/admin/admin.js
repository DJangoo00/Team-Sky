function acc_mod(acc){  //  Acciones del Modulo
	var accion=acc;
    $("#estructura").html('<span class="spinner-border spinner-border-sm text-primary mr-2"></span> verificando');
	if(acc=='ini'){   		
		$("#estructura").load('generador.php',{accion:accion});
	}
	if(acc=='lst_veterinarios'){   		
		$("#estructura").load('generador.php',{accion:accion});
	}
}