$(document).ready(function(){
    // DataTable en español
    $('#ranking').DataTable({
        "pageLength": 50,
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
            console.log(data);
            resultado=JSON.parse(data);
            $("#operador_nombre").empty();
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