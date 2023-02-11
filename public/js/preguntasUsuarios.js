$(document).ready(function(){
    cargarPreguntas();

    $(document).on('click','.botonRespuestas', function (event) {
        event.preventDefault();
        var id=$(this).attr('id');
        $.getJSON('/respuesta/usuario/'+id,null,function(respuestas){
            //console.log(respuestas);
            let codigo="";
            codigo+="LISTA DE RESPUESTAS<br/>";
            for(let i=0;i<respuestas.length;i++){       
                codigo+="<p>"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p>"+respuestas[i].nombreAutor+"</p>";
                codigo+="<p>"+respuestas[i].texto+"</p>";   
            }
            codigo+="<br/>FIN DE LA LISTA DE RESPUESTAS";
            $("#bloqueRespuestas"+id).html(codigo);
        });
    });

    function cargarPreguntas(){
        $.getJSON("/pregunta/usuario",null,function(preguntas){
            let codigo="";
            for(let i=0;i<preguntas.length;i++){
                codigo+="<div class='bloquePregunta'>";
                codigo+="<p>"+formatDate(preguntas[i].fecha)+"</p>";
                codigo+="<p>"+preguntas[i].nombreAutor+"</p>";
                codigo+="<p>"+preguntas[i].texto+"</p>";
                codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                codigo+="<input type='text' name='textoRespuesta'/>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                codigo+="<input type='submit' value='Enviar respuesta' class='enviarRespuesta' class='btn btn-primary'>";
                codigo+="</form>";
                codigo+="<button type='button' class='botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas anteriores</button>";
                codigo+="<div id='bloqueRespuestas"+preguntas[i].id+"'></div>";
                codigo+="</div>";
            }
            $('#listadoPreguntas').html(codigo); 
        });
    }

    function formatDate (stringFecha) {
        let fecha=Date.parse(stringFecha);
        let date= new Date(fecha);
        return date.getDate()+"-" + date.getMonth() + "-" + date.getFullYear() + " " +  ('0' + date.getHours()).slice(-2) + ":" + ('0' + date.getMinutes()).slice(-2) + ":" + ('0' + date.getSeconds()).slice(-2) + ' ' + (date.getHours() < 12 ? 'AM' : 'PM');
    } 

});
