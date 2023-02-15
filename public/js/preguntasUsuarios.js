$(document).ready(function(){
    cargarPreguntas();



    $(document).on('submit','.enviarRespuesta', function (event) {
        event.preventDefault();
        var inputs= $(this).find("input");
        var id=inputs[1].value;
        let respuestaEnviada=$(this).serialize();
        $.post("/respuesta/new",respuestaEnviada,function(respuestasRecuperadas){
            let codigo="";
            for(let i=0;i<respuestasRecuperadas.length;i++){
                codigo+="<p>"+formatDate(respuestasRecuperadas[i].fecha)+"</p>";
                codigo+="<p>"+respuestasRecuperadas[i].nombreAutor+"</p>";
                codigo+="<p>"+respuestasRecuperadas[i].texto+"</p>"; 
                if(respuestasRecuperadas[i].User.id== sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestasRecuperadas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestasRecuperadas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestasRecuperadas[i].id+`/edit">Actualizar respuesta</a>`; 
                } 
            }
            $("#bloqueRespuestas"+id).html(codigo);
        });
        inputs[0].value=""; 
    });


    $(document).on('click','.botonRespuestas', function (event) {
        event.preventDefault();
        var id=$(this).attr('id');
        $.getJSON('/respuesta/usuario/'+id,null,function(respuestas){
            let codigo="";
            //codigo+="LISTA DE RESPUESTAS<br/>";
            for(let i=0;i<respuestas.length;i++){       
                codigo+="<p>"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p>"+respuestas[i].nombreAutor+"</p>";
                codigo+="<p>"+respuestas[i].texto+"</p>";   
                if(respuestas[i].User.id== sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }
            }
            //codigo+="<br/>FIN DE LA LISTA DE RESPUESTAS";
            $("#bloqueRespuestas"+id).html(codigo);
        });
    });

    $(document).on('submit','.borrarRespuesta', function (event) {
        event.preventDefault();
        var inputs= $(this).find("input");
        var idRespuesta=inputs[0].value;
        var idPregunta=inputs[1].value;
        $.post("/respuesta/delete/"+idRespuesta,null,function(respuestas){
            let codigo="";
            for(let i=0;i<respuestas.length;i++){       
                codigo+="<p>"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p>"+respuestas[i].nombreAutor+"</p>";
                codigo+="<p>"+respuestas[i].texto+"</p>"; 
                if(respuestas[i].User.id== sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }  
            }
            $("#bloqueRespuestas"+idPregunta).html(codigo);
              
        });
    });

    $(document).on('submit','.borrarPregunta', function (event) {
        event.preventDefault();
        var inputs= $(this).find("input");
        var idPregunta=inputs[0].value;
        $.post("/pregunta/delete/usuario/"+idPregunta,null,function(preguntas){
            let codigo="";
            for(let i=0;i<preguntas.length;i++){
                codigo+="<div class='bloquePregunta'>";
                codigo+="<p>"+formatDate(preguntas[i].fecha)+"</p>";
                codigo+="<p>"+preguntas[i].nombreAutor+"</p>";
                codigo+="<p>"+preguntas[i].texto+"</p>";
                if(sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                    codigo+="<input type='text' name='textoRespuesta'/>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Enviar respuesta' class='enviarRespuesta btn btn-primary'>";
                    codigo+="</form>";
                }
                if(preguntas[i].User.id== sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='borrarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                    codigo+="</form>";
                    codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntas[i].id+`/edit">Actualizar pregunta</a>`;
                }
                codigo+="<button type='button' class='botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas</button>";
                codigo+="<div id='bloqueRespuestas"+preguntas[i].id+"'></div>";
                codigo+="</div>";
            }
            $('#listadoPreguntas').html(codigo); 
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
                codigo+="<button type='button' class='botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas</button>";
                if(preguntas[i].User.id== sessionStorage.getItem("id")){
                    codigo+="<form method='post' class='borrarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                    codigo+="</form>";
                    codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntas[i].id+`/edit">Actualizar pregunta</a>`;
                }
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
