
$(document).ready(function(){
    cargarPreguntas();

    $(document).on('click','#enviarPregunta', function (event) {
        event.preventDefault();
        let id=document.querySelector("#productoId").innerHTML;
        let preguntaEnviada=$("#formularioPregunta").serialize();
        $.post("/pregunta/new/"+id,preguntaEnviada,function(preguntaRecuperada){
            let codigo="";
            codigo+="<div class='bloquePregunta'>";
            codigo+="<p>"+formatDate(preguntaRecuperada.fecha)+"</p>";
            codigo+="<p>"+preguntaRecuperada.nombreAutor+"</p>";
            codigo+="<p>"+preguntaRecuperada.texto+"</p>";
            codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
            codigo+="<input type='text' name='textoRespuesta' class='texto'/>";
            codigo+="<input type='hidden' name='idPregunta' class='id' value='"+preguntaRecuperada.id+"'>";
            codigo+="<input type='submit' value='Enviar respuesta' class='botonEnviarRespuesta btn btn-primary'>";
            codigo+="</form>";
            if(preguntaRecuperada.User.id== sessionStorage.getItem("id")){
                codigo+="<form method='post' class='borrarPregunta'>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                codigo+="</form>";

/*                 codigo+="<form method='post' class='actualizarPregunta'>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                codigo+="<input type='submit' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                codigo+="</form>"; */
                codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntaRecuperada.id+`/edit">Actualizar pregunta</a>`;
            }
            codigo+="<button type='button' class='botonRespuestas' id='"+preguntaRecuperada.id+"'>Ver respuestas</button>";
            codigo+="<div id='bloqueRespuestas"+preguntaRecuperada.id+"'></div>";
            codigo+="</div>";
            codigo+=$('#listadoPreguntas').html();
            $('#listadoPreguntas').html(codigo);
            $('#inputTexto').val("");
        });
    });

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
                    codigo+="<form method='post' class='actualizarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestasRecuperadas[i].id+"'>";
                    codigo+="<input type='text' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestasRecuperadas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestasRecuperadas[i].id+`/edit">Actualizar respuesta</a>`; 
                } 
            }
            $("#bloqueRespuestas"+id).html(codigo);
        });
        inputs[0].value=""; 
    });


    $(document).on('click','.botonRespuestas', function (event) {
        event.preventDefault();
        var id=$(this).attr('id');
        $.getJSON('/respuesta/'+$(this).attr('id'),null,function(respuestas){
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

                    codigo+="<form method='post' class='actualizarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }  
            }
            $("#bloqueRespuestas"+id).html(codigo);
        });
    });

    $(document).on('submit','.actualizarRespuesta', function (event) {
        event.preventDefault();
        var inputs= $(this).find("input");
        var idRespuesta=inputs[0].value;
        var idPregunta=inputs[2].value;
        let respuestaActualizada=$(this).serialize();
        $.post("/respuesta/edit/"+idRespuesta,respuestaActualizada,function(respuestas){
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
                    codigo+="<form method='post' class='actualizarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }
            } 
            $("#bloqueRespuestas"+idPregunta).html(codigo);
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
                    codigo+="<form method='post' class='actualizarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }  
            }
            $("#bloqueRespuestas"+idPregunta).html(codigo);
              
        });
    });

    $(document).on('submit','.borrarPregunta', function (event) {
        event.preventDefault();
        var inputs= $(this).find("input");
        var idPregunta=inputs[0].value;
        $.post("/pregunta/delete/"+idPregunta,null,function(preguntas){
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
        let id=document.querySelector("#productoId").innerHTML;
        $.getJSON("/pregunta/"+id,null,function(preguntas){
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
    }

    function formatDate (stringFecha) {
        let fecha=Date.parse(stringFecha);
        let date= new Date(fecha);
        return date.getDate()+"-" + date.getMonth() + "-" + date.getFullYear() + " " +  ('0' + date.getHours()).slice(-2) + ":" + ('0' + date.getMinutes()).slice(-2) + ":" + ('0' + date.getSeconds()).slice(-2) + ' ' + (date.getHours() < 12 ? 'AM' : 'PM');
    } 

});








 