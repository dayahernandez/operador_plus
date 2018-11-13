function validarCedula(cedula){
	$.ajax({  
        type: 'post',
        url: 'includes/funciones/ranking_general.php',
        data: {
        	'funcion': 'validarCedula',
        	'cedula': cedula
        },
        success: function(data){
            console.log(data);
        	resultado=JSON.parse(data);
        	if(resultado=='false'){
        		$("#consultar").hide('fade','',1000);
                $("#alerta_cedula").show('fade','',1000);
        	}else{
                $("#consultar").show('fade','',1000);
                $("#alerta_cedula").hide('fade','',1000);
            }
        }
    });
}