$(document).ready(function(){
    // DataTable en español
    $('#ranking').DataTable({
        "pageLength": 50,
        "scrollY": 500,
        "scrollX":true,
        "language":{
            "lengthMenu":"Mostrar _MENU_ registros por página.",
            "zeroRecords": "Lo sentimos. No se encontraron registros.",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "No hay registros aún.",
            "infoFiltered": "(filtrados de un total de _MAX_ registros)",
            "search" : "Búsqueda",
            "LoadingRecords": "Cargando ...",
            "Processing": "Procesando...",
            "SearchPlaceholder": "Comience a teclear...",
            "paginate": {
                "previous": "Anterior",
                "next": "Siguiente", 
            }
        },
        "fixedColumns":{
            "leftColumns": 5
        }
    });
});
function verDetalleHallazgos(cedula,mes,anio,tipo){
    $.ajax({  
        type: 'post',
        url: 'ranking_general.php',
        data: {
            'funcion': 'verDetalleHallazgos',
            'cedula': cedula,
            'mes': mes,
            'anio': anio,
            'tipo':tipo
        },
        success: function(data){
            resultado=JSON.parse(data);
            $("#operador_nombre_mantenimiento").empty();
            $("#hallazgos").empty();
            $("#operador_nombre").html(resultado[0]['operador']);
            for(i=0;i<resultado.length;i++){
                row=$("<tr>");
                fecha=$("<td>");
                fecha.html(resultado[i]['man_fecha']);
                tipo_hallazgo=$("<td>");
                tipo_hallazgo.html(resultado[i]['tipo_hallazgo']);
                descripcion=$("<td>");
                descripcion.html(resultado[i]['man_hallazgo']);
                checklist=$("<td>");
                checklist.html(resultado[i]['man_checklist']);
                $(row).append(fecha);
                $(row).append(tipo_hallazgo);
                $(row).append(descripcion);
                $(row).append(checklist);
                $("#hallazgos").append(row);
            }
            $('#ModalDetalleHallazgos').modal('show');
        }
    });
}
function verValorAgregado(cedula,mes,anio,tipo){
    $.ajax({  
        type: 'post',
        url: 'ranking_general.php',
        data: {
            'funcion': 'verValorAgregado',
            'cedula': cedula,
            'mes': mes,
            'anio': anio,
            'tipo':tipo
        },
        success: function(data){
            resultado=JSON.parse(data);
            $("#operador_nombre_valor").empty();
            $("#operador_nombre").empty();
            $("#novedades").empty();
            $("#operador_nombre").html(resultado[0]['operador']);
            for(i=0;i<resultado.length;i++){
                row=$("<tr>");
                fecha=$("<td>");
                if(resultado[i]['val_fecha_fin']!=null){                         
                    fecha.html(resultado[i]['val_fecha']+" / "+resultado[i]['val_fecha_fin']);
                }else{
                    fecha.html(resultado[i]['val_fecha']);
                }
                tipo_novedad=$("<td>");
                tipo_novedad.html(resultado[i]['tipo_novedad']);
                observacion=$("<td>");
                if(resultado[i]['val_dias']!=null){
                    observacion.html(resultado[i]['val_observacion']+" "+resultado[i]['val_dias']+" DÍAS.");
                }else{
                    observacion.html(resultado[i]['val_observacion']);
                }
                $(row).append(fecha);
                $(row).append(tipo_novedad);
                $(row).append(observacion);
                $("#novedades").append(row);
            }
            $('#ModalDetalleValor').modal('show');
        }
    });
}