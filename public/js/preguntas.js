
$(document).ready(function(){
    cargarPreguntas();

    $(document).on('click','#enviarPregunta', function (event) {
        event.preventDefault();
        let id=document.querySelector("#productoId").innerHTML;
        let preguntaEnviada=$("#formularioPregunta").serialize();
        $.post("/pregunta/new/"+id,preguntaEnviada,function(preguntaRecuperada){
            let codigo="";
            codigo+="<div class=' text-center'>";
            codigo+="<div class='bloquePregunta card' style='width: 40vw; margin-left: 25vw;'>";
            codigo+="<p>"+formatDate(preguntaRecuperada.fecha)+"</p>";
            codigo+="<p>"+preguntaRecuperada.nombreAutor+"</p>";
            codigo+="<p style='background-color: #eaeaea96;'>"+preguntaRecuperada.texto+"</p>";
            codigo+='<button class="btn btn-success mostrarForm" id="mFor'+preguntaRecuperada.id+'" onclick=esc2("'+preguntaRecuperada.id+'") >Responder</button>';
                    codigo+="<div class='esc' style='display:none' id='esc2"+preguntaRecuperada.id+"' >"
                    codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                    codigo+="<input type='text' name='textoRespuesta'/>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                    codigo+="<input type='submit' value='Enviar respuesta' class='enviarRespuesta' class='btn btn-primary' onclick=esc3('"+preguntaRecuperada.id+"')>";
                    codigo+="</form>";
                    codigo+="</div>";
            if(preguntaRecuperada.User.id== sessionStorage.getItem("id")){
                codigo+="<form method='post' class='borrarPregunta'>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                codigo+="</form>";



                codigo+='<button class="btn btn-success mostrarForm" id="mForm'+preguntaRecuperada.id+'" onclick=esc("'+preguntaRecuperada.id+'") >Editar pregunta</button>';
                    codigo+="<div class='esc' style='display:none' id='esc"+preguntaRecuperada.id+"' >"
                    codigo+="<form method='post' class='actualizarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                    codigo+="<input type='text' name='textoPregunta' value=''>";
                    codigo+="<input type='submit' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                    codigo+="</form>"; 
                    codigo+="</div>";

                // codigo+="<form method='post' class='actualizarPregunta'>";
                // codigo+="<input type='hidden' name='idPregunta' value='"+preguntaRecuperada.id+"'>";
                // codigo+="<input type='text' name='textoPregunta' value=''>";
                // codigo+="<input type='submit' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                // codigo+="</form>"; 
                //codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntaRecuperada.id+`/edit">Actualizar pregunta</a>`;
            }
            codigo+="<button type='button' class='botonRespuestas' id='"+preguntaRecuperada.id+"'>Ver respuestas</button>";
            codigo+="<div id='bloqueRespuestas"+preguntaRecuperada.id+"'></div>";
            codigo+="</div>";
            codigo+="</div>";
            codigo+=$('#listadoPreguntas').html();
            $('#listadoPreguntas').html(codigo);
            $('#inputTexto').val("");
        });
    });

    $(document).on('submit','.actualizarPregunta', function (event) {
        event.preventDefault();
        let id=document.querySelector("#productoId").innerHTML;
        let preguntaActualizada=$(this).serialize();
        $.post("/pregunta/edit/"+id,preguntaActualizada,function(preguntas){
            let codigo="";
            for(let i=0;i<preguntas.length;i++){
                
                codigo+="<div class='container text-center >";
                codigo+="<div class='bloquePregunta card' style='width: 80%;margin.bottom: 4px; border-bottom: solid 1px #B5B2B2;'>";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<div style='width: 45%; text-align: left; margin-left:2%; '>"+preguntas[i].nombreAutor+"</div>";
                codigo+="<div style='width: 45%;position: relative; text-align: right;margin-right:2%;'>"+formatDate(preguntas[i].fecha)+"</div>";
                codigo+="</div>";
                codigo+="<p style='margin:2%; text-align: left;'>"+preguntas[i].texto+"</p>";

                codigo+="<div style='display:flex; justify-content: space-between;'>";

                codigo+='<button class="btn btn-success mostrarForm" id="mFor'+preguntas[i].id+'" onclick=esc2("'+preguntas[i].id+'") >Responder</button>';
                codigo+="<div class='esc' style='display:none; width:25%;' id='esc2"+preguntas[i].id+"' >"
                codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                codigo+="<input type='text' style='margin:1%;' name='textoRespuesta'/>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                codigo+="<input type='submit' style='margin:1%;' value='Enviar respuesta' class='enviarRespuesta btn btn-primary' onclick=esc3('"+preguntas[i].id+"')>";
                codigo+="</form>";
                codigo+="</div>";
                console.log(sessionStorage.getItem("role"));
                if(preguntas[i].User.id== sessionStorage.getItem("id")   || "ROLE_ADMIN"== sessionStorage.getItem("role")){
                    codigo+="<form method='post' class='borrarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                    codigo+="</form>";

                    codigo+='<button class="btn btn-success mostrarForm" id="mForm'+preguntas[i].id+'" onclick=esc("'+preguntas[i].id+'") >Editar pregunta</button>';
                    codigo+="<div class='esc' style='display:none; width:25%;' id='esc"+preguntas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='text' style='margin:1%;' name='textoPregunta' value=''>";
                    codigo+="<input type='submit' style='margin:1%;' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                    codigo+="</form>"; 
                    codigo+="</div>";
                    //codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntas[i].id+`/edit">Actualizar pregunta</a>`;
                }
                codigo+="<button type='button' class='btn btn-primary botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas</button>";
                codigo+="</div>";
                codigo+="<div id='bloqueRespuestas"+preguntas[i].id+"'></div>";
                codigo+="</div>";
                codigo+="</div>";

            }if(preguntas.length<1){
                codigo+="<div>Este usuario no ha realizado ninguna pregunta</div>";
            }
            $('#listadoPreguntas').html(codigo); 
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
                codigo+="<div style=' background-color: grey; color:white;' > ";
                codigo+="<div > ";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<p style=' margin-left:2%;' >"+formatDate(respuestasRecuperadas[i].fecha)+"</p>";
                codigo+="<p style=' margin-right:2%;'>"+respuestasRecuperadas[i].nombreAutor+"</p>";
                codigo+="</div>";
                codigo+="<p style='text-align: left; margin-left:2%;'>"+respuestasRecuperadas[i].texto+"</p>";   
                codigo+="</div>";
                
                if(respuestasRecuperadas[i].User.id== sessionStorage.getItem("id")   || "ROLE_ADMIN"== sessionStorage.getItem("role")){
                                                      
                    codigo+="<div style='display:flex; justify-content: space-around; '>";
                    
                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestasRecuperadas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestasRecuperadas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin-bottom:2%;' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";

                    codigo+='<button class="btn btn-success mostrarForm" id="mostrarForm'+respuestasRecuperadas[i].id+'" onclick=muestra("'+respuestasRecuperadas[i].id+'") >Editar respuestas</button>';
                    codigo+="<div class='esconder' style='display:none' id='esconder"+respuestasRecuperadas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarRespuesta' id='formularioRespuesta"+respuestasRecuperadas[i].id+"'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestasRecuperadas[i].id+"'>";
                    codigo+="<input type='text' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestasRecuperadas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin-bottom:2%;' value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger' >";
                    codigo+="</form>";
                    codigo+="</div>";
                    codigo+="</div>";
                    codigo+="</div>";
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
        var codigo="xxx";
        $.getJSON('/respuesta/'+$(this).attr('id'),null,function(respuestas){
            
            for(let i=0;i<respuestas.length;i++){     
                codigo+="<div style=' background-color: grey; color:white;' > ";
                codigo+="<div > ";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<p style=' margin-left:2%;' >"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p style=' margin-right:2%;'>"+respuestas[i].nombreAutor+"</p>";
                codigo+="</div>";
                codigo+="<p style='text-align: left; margin-left:2%;'>"+respuestas[i].texto+"</p>";   
                codigo+="</div>";
                if(respuestas[i].User.id== sessionStorage.getItem("id")  || "ROLE_ADMIN"== sessionStorage.getItem("role") ){
                    
                    codigo+="<div style='display:flex; justify-content: space-around; '>";

                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin-bottom:2%;' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    
                    //codigo+='<button class="btn btn-success mostrarForm" id="'+respuestas[i].id+'" >Editar respuestas</button>';
                    codigo+='<button class="btn btn-success mostrarForm" style="margin-bottom:2%;" id="mostrarForm'+respuestas[i].id+'" onclick=muestra("'+respuestas[i].id+'") >Editar respuestas</button>';
                    codigo+="<div class='esconder' style='display:none' id='esconder"+respuestas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarRespuesta' id='formularioRespuesta"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' style='margin:1%; color:black;' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin:1%; value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger' >";
                    codigo+="</form>";
                    codigo+="</div>"
                    codigo+="</div>";
                    codigo+="</div>";

                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }
            }
            
            //codigo+="<br/>FIN DE LA LISTA DE RESPUESTAS";
            console.log(codigo);
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
                codigo+="<div style=' background-color: grey; color:white;' > ";
                codigo+="<div > ";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<p style=' margin-left:2%;' >"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p style=' margin-right:2%;'>"+respuestas[i].nombreAutor+"</p>";
                codigo+="</div>";
                codigo+="<p style='text-align: left; margin-left:2%;'>"+respuestas[i].texto+"</p>";   
                codigo+="</div>";
                if(respuestas[i].User.id== sessionStorage.getItem("id")  || "ROLE_ADMIN"== sessionStorage.getItem("role") ){
                    
                    codigo+="<div style='display:flex; justify-content: space-around; '>";

                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin-bottom:2%;' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    
                    //codigo+='<button class="btn btn-success mostrarForm" id="'+respuestas[i].id+'" >Editar respuestas</button>';
                    codigo+='<button class="btn btn-success mostrarForm" style="margin-bottom:2%;" id="mostrarForm'+respuestas[i].id+'" onclick=muestra("'+respuestas[i].id+'") >Editar respuestas</button>';
                    codigo+="<div class='esconder' style='display:none' id='esconder"+respuestas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarRespuesta' id='formularioRespuesta"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' style='margin:1%; color:black;' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin:1%; value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger' >";
                    codigo+="</form>";
                    codigo+="</div>"
                    codigo+="</div>";
                    codigo+="</div>";

                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }
            }
            
            //codigo+="<br/>FIN DE LA LISTA DE RESPUESTAS";
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
                codigo+="<div style=' background-color: grey; color:white;' > ";
                codigo+="<div > ";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<p style=' margin-left:2%;' >"+formatDate(respuestas[i].fecha)+"</p>";
                codigo+="<p style=' margin-right:2%;'>"+respuestas[i].nombreAutor+"</p>";
                codigo+="</div>";
                codigo+="<p style='text-align: left; margin-left:2%;'>"+respuestas[i].texto+"</p>";   
                codigo+="</div>";
                if(respuestas[i].User.id== sessionStorage.getItem("id")  || "ROLE_ADMIN"== sessionStorage.getItem("role") ){
                    
                    codigo+="<div style='display:flex; justify-content: space-around; '>";

                    codigo+="<form method='post' class='borrarRespuesta'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin-bottom:2%;' value='Borrar Respuesta' class='borrarRespuestaInput btn btn-danger'>";
                    codigo+="</form>";
                    
                    //codigo+='<button class="btn btn-success mostrarForm" id="'+respuestas[i].id+'" >Editar respuestas</button>';
                    codigo+='<button class="btn btn-success mostrarForm" style="margin-bottom:2%;" id="mostrarForm'+respuestas[i].id+'" onclick=muestra("'+respuestas[i].id+'") >Editar respuestas</button>';
                    codigo+="<div class='esconder' style='display:none' id='esconder"+respuestas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarRespuesta' id='formularioRespuesta"+respuestas[i].id+"'>";
                    codigo+="<input type='hidden' name='idRespuesta' value='"+respuestas[i].id+"'>";
                    codigo+="<input type='text' style='margin:1%; color:black;' name='textoRespuesta' value=''>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+respuestas[i].pregunta.id+"'>";
                    codigo+="<input type='submit' style='margin:1%; value='Actualizar Respuesta' class='actualizarRespuestaInput btn btn-danger' >";
                    codigo+="</form>";
                    codigo+="</div>"
                    codigo+="</div>";
                    codigo+="</div>";

                    //codigo+=`<a class="btn btn-warning" href="/respuesta/`+respuestas[i].id+`/edit">Actualizar respuesta</a>`;
                }
            }
            
            //codigo+="<br/>FIN DE LA LISTA DE RESPUESTAS";
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
                codigo+="<div class='container text-center'>";
                codigo+="<div class='bloquePregunta card' style='width: 40vw; margin-left: 25vw;'>";
                codigo+="<p>"+formatDate(preguntas[i].fecha)+"</p>";
                codigo+="<p>"+preguntas[i].nombreAutor+"</p>";
                codigo+="<p>"+preguntas[i].texto+"</p>";
                if(sessionStorage.getItem("id")){
                codigo+='<button class="btn btn-success mostrarForm" id="mFor'+preguntas[i].id+'" onclick=esc2("'+preguntas[i].id+'") >Responder</button>';
                codigo+="<div class='esc' style='display:none' id='esc2"+preguntas[i].id+"' >"
                codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                codigo+="<input type='text' name='textoRespuesta'/>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                codigo+="<input type='submit' value='Enviar respuesta' class='enviarRespuesta' class='btn btn-primary' onclick=esc3('"+preguntas[i].id+"')>";
                codigo+="</form>";
                codigo+="</div>";
                }
                if(preguntas[i].User.id== sessionStorage.getItem("id")  || "ROLE_ADMIN"== sessionStorage.getItem("role")){
                    codigo+="<form method='post' class='borrarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                    codigo+="</form>";

                    codigo+='<button class="btn btn-success mostrarForm" id="mForm'+preguntas[i].id+'" onclick=esc("'+preguntas[i].id+'") >Editar pregunta</button>';
                    codigo+="<div class='esc' style='display:none' id='esc"+preguntas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='text' name='textoPregunta' value=''>";
                    codigo+="<input type='submit' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                    codigo+="</form>"; 
                    codigo+="</div>";
                    //codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntas[i].id+`/edit">Actualizar pregunta</a>`;
                }
                codigo+="<button type='button' class='botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas</button>";
                codigo+="<div id='bloqueRespuestas"+preguntas[i].id+"'></div>";
                codigo+="</div>";
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
                
                codigo+="<div class='container text-center >";
                codigo+="<div class='bloquePregunta card' style='width: 80%;margin.bottom: 4px; border-bottom: solid 1px #B5B2B2;'>";
                codigo+="<div style='display:flex; justify-content: space-between;'>";
                codigo+="<div style='width: 45%; text-align: left; margin-left:2%; '>"+preguntas[i].nombreAutor+"</div>";
                codigo+="<div style='width: 45%;position: relative; text-align: right;margin-right:2%;'>"+formatDate(preguntas[i].fecha)+"</div>";
                codigo+="</div>";
                codigo+="<p style='margin:2%; text-align: left;'>"+preguntas[i].texto+"</p>";

                codigo+="<div style='display:flex; justify-content: space-between;'>";

                codigo+='<button class="btn btn-success mostrarForm" id="mFor'+preguntas[i].id+'" onclick=esc2("'+preguntas[i].id+'") >Responder</button>';
                codigo+="<div class='esc' style='display:none; width:25%;' id='esc2"+preguntas[i].id+"' >"
                codigo+="<form method='post' class='enviarRespuesta' action='/respuesta/new'>";
                codigo+="<input type='text' style='margin:1%;' name='textoRespuesta'/>";
                codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                codigo+="<input type='submit' style='margin:1%;' value='Enviar respuesta' class='enviarRespuesta btn btn-primary' onclick=esc3('"+preguntas[i].id+"')>";
                codigo+="</form>";
                codigo+="</div>";
                console.log(sessionStorage.getItem("role"));
                if(preguntas[i].User.id== sessionStorage.getItem("id")   || "ROLE_ADMIN"== sessionStorage.getItem("role")){
                    codigo+="<form method='post' class='borrarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='submit' value='Borrar pregunta' class='borrarPreguntaInput btn btn-danger'>";
                    codigo+="</form>";

                    codigo+='<button class="btn btn-success mostrarForm" id="mForm'+preguntas[i].id+'" onclick=esc("'+preguntas[i].id+'") >Editar pregunta</button>';
                    codigo+="<div class='esc' style='display:none; width:25%;' id='esc"+preguntas[i].id+"' >"
                    codigo+="<form method='post' class='actualizarPregunta'>";
                    codigo+="<input type='hidden' name='idPregunta' value='"+preguntas[i].id+"'>";
                    codigo+="<input type='text' style='margin:1%;' name='textoPregunta' value=''>";
                    codigo+="<input type='submit' style='margin:1%;' value='Actualizar pregunta' class='actualizarPreguntaInput btn btn-danger'>";
                    codigo+="</form>"; 
                    codigo+="</div>";
                    //codigo+=`<a class="btn btn-warning" href="/pregunta/`+preguntas[i].id+`/edit">Actualizar pregunta</a>`;
                }
                codigo+="<button type='button' class='btn btn-primary botonRespuestas' id='"+preguntas[i].id+"'>Ver respuestas</button>";
                codigo+="</div>";
                codigo+="<div id='bloqueRespuestas"+preguntas[i].id+"'></div>";
                codigo+="</div>";
                codigo+="</div>";

            }if(preguntas.length<1){
                codigo+="<div>Este usuario no ha realizado ninguna pregunta</div>";
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

function muestra(id) {
    console.log(id);
    
        form=document.getElementById("esconder"+id).style.display= "block";
        btn=document.getElementById("mostrarForm"+id).style.display= "none";
      
    //const variable = form.style.display= "block";
} 
function esc(id) {
    console.log(id);
    
        form=document.getElementById("esc"+id).style.display= "block";
        btn=document.getElementById("mForm"+id).style.display= "none";
      
    //const variable = form.style.display= "block";
}     

function esc2(id) {
    console.log(id);
    
        form=document.getElementById("esc2"+id).style.display= "block";
        btn=document.getElementById("mFor"+id).style.display= "none";
      
    //const variable = form.style.display= "block";
}  
function esc3(id) {
    console.log(id);
    setTimeout(() => {
        form=document.getElementById("esc2"+id).style.display= "none";
        btn=document.getElementById("mFor"+id).style.display= "block";
      }, "500")
        
      
    //const variable = form.style.display= "block";
}  

