// Abre la conexión con la base de datos
var request = indexedDB.open("carrito", 1);
var db;
//ejecutar al cargar

var listaCarrito = document.querySelector('.listaCarrito')

// Crea el almacén de objetos y comprobamos que no exista 
request.onupgradeneeded = function(event) {

  indexedDB.databases().then(function(dbs) {
    var dbExists = dbs.some(function(db) {
        return db.name === "carrito";
    });
    if (dbExists) {
        console.log("La base de datos carrito existe.");
    } else {
        console.log("La base de datos carrito no existe.");
    }
});
    var db = event.target.result;
    var objectStore = db.createObjectStore("carrito", { keyPath: "id", autoIncrement: true });
    objectStore.createIndex("imagen", "imagen", { unique: false });
    objectStore.createIndex("name", "name", { unique: true });
    objectStore.createIndex("price", "price", { unique: false });
    objectStore.createIndex("quantity", "quantity", { unique: false });
    objectStore.createIndex("cantidadInicial", "cantidadInicial",  { unique: false });
};


// -----------------------------------------------Pasar datos al carrito------------------------------------------------------


// Abre una transacción para operar con el almacén de objetos
request.onsuccess = function(event) {
    db = event.target.result;

    const addToShoppingCartButtons = document.querySelectorAll('.addToCart');
    addToShoppingCartButtons.forEach((addToCartButton) => {
    addToCartButton.addEventListener('click', addToCart);
    });
    // Recogemos los datos de  mediante la clase utilizando querySelector  

function addToCart() {
    var transaction = db.transaction("carrito", "readwrite");
    var objectStore = transaction.objectStore("carrito");
    const itemName = document.querySelector('.productoNombre').innerText;
    console.log(itemName);
    const itemPrice = document.querySelector('.productoPrecio').innerText;
    const itemImagen = document.querySelector('.productoImagen').src;
    const itemQuantity = document.querySelector('.productoCantidad').innerText;
    const itemQuantityInicial = 1;
    var item ={ imagen: itemImagen, name: itemName, price: itemPrice, quantity: itemQuantity, cantidadInicial: itemQuantityInicial };
    var request = objectStore.add(item);
    request.onsuccess = function() {
        console.log("Producto añadido al carrito.");
    };
    request.onerror = function() {
        console.log("Error, el producto ya esta en  el carrito.");
    };
}

};

// -------------------------------Pasamos los datos almacenados en nuestro indexDB a nuestro carrito-------------------------------------

onload = function(){
    getCartItems();
}

    // Obtiene los productos del carrito
    var listaProdCantidad;
    function getCartItems() {
        //div carrito
        console.log("flag");
        listaCarrito.innerHTML=" ";
        var transaction = db.transaction(["carrito"], "readonly");
        var objectStore = transaction.objectStore("carrito");
        var total=0;
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result;
            if(cursor){
                //separaren diferentes bloques div cada producto
                var tarjetaProducto = document.createElement('div');
                listaCarrito.appendChild(tarjetaProducto);
                // --------------------------------CARRTIO ----------------------
                var listaProdImagen = document.createElement('div');
                var imagen = document.createElement('img');
                imagen.classList.add("mini-imagen");
                imagen.src= cursor.value.imagen;
                listaProdImagen.appendChild(imagen);
                var listaProdNombre = document.createElement('div');
                listaProdNombre.classList.add( "nombreProducto");
                listaProdNombre.innerHTML = cursor.value.name;
                var listaProdPrecio = document.createElement('div');
                listaProdPrecio.innerHTML = `${cursor.value.price}€`;
                listaProdCantidad = document.createElement('div');
                listaProdCantidad.innerHTML = `Cantidad   ${cursor.value.cantidadInicial}`;
                if(cursor.value.cantidadInicial 
                    == cursor.value.quantity){
                    listaProdCantidad.innerHTML += `   Stock maximo `;
                } 

                // --------------------- Actaulizar el precio total --------------------

                total += cursor.value.price * cursor.value.cantidadInicial;
                

                tarjetaProducto.appendChild(listaProdImagen);
                tarjetaProducto.appendChild(listaProdNombre);
                tarjetaProducto.appendChild(listaProdPrecio);
                // tarjetaProducto.appendChild(listaProdCantidad);
                tarjetaProducto.innerHTML +=`<button class="btn btn-primary" onclick="removeQuantity('${listaProdNombre.innerHTML}')">-</button>`+listaProdCantidad.innerHTML+`<button class="btn btn-primary" onclick="addQuantity('${listaProdNombre.innerHTML}')">+</button>`;

        
                
                tarjetaProducto.innerHTML +=`<br><button class="btn btn-danger" onclick="removeFromCart('${listaProdNombre.innerHTML}')">Eliminar</button>`



                // --------------------------------------- Botones de agregar y quitar cantidad de un producto 
                // tarjetaProducto.innerHTML +=`<button class="btn btn-info" onclick="removeQuantity('${listaProdNombre.innerHTML}')">-</button>`
                // tarjetaProducto.innerHTML +=`<button class="btn btn-info" onclick="addQuantity('${listaProdNombre.innerHTML}')">+</button>`
                cursor.continue();
                // console.log(cursor.key)
                
            }else{
                console.log("todo mostrado");
            }
        }
        setTimeout(() => {

            listaCarrito.innerHTML += `<br><div> Total: ${total}€</div>`
            listaCarrito.innerHTML += `<br><button class="btn btn-warning" onclick="vaciarCarro()" >Vaciar</button>`
            listaCarrito.innerHTML += `<button class="btn btn-success" onclick="procesarCompra()" >Comprar</button>`
            listaCarrito.innerHTML += `<button class="btn btn-danger" onclick="esconder()" >Cerrar</button>`
        }, 100)
    }





// --------------------------Disminuir la cantidad del prodcuto ------------------------------------

function removeQuantity(nombre) {
    listaCarrito.innerHTML=" ";
    var transaction = db.transaction(["carrito"], "readwrite");
    var objectStore = transaction.objectStore("carrito");
    objectStore.openCursor().onsuccess = function(event){
        var cursor = event.target.result;
        if(cursor){
            if(cursor.value.name === nombre){
                var updateData = cursor.value;
                updateData.cantidadInicial-=1
                var req = cursor.update(updateData);
                req.onsuccess = function(){
                } 
                if(cursor.value.cantidadInicial===0){
                    removeFromCart(cursor.value.name);
                    window.location.href = "http://localhost:8000/producto/";
                }
            }
            cursor.continue();
            
        }
    }
    getCartItems();
}


    //--------------------------------Añadir cantidad--------------------------------------------------------
    function addQuantity(nombre) {
        listaCarrito.innerHTML=" ";
        var transaction = db.transaction(["carrito"], "readwrite");
        var objectStore = transaction.objectStore("carrito");
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result; 
            if(cursor){
                if(cursor.value.name === nombre){
                    var cantidadMaxima = cursor.value.quantity;
                    var updateData = cursor.value;
                    if( (cursor.value.cantidadInicial) <  cantidadMaxima){
                        updateData.cantidadInicial+=1;
                    }   
                    var req = cursor.update(updateData);
                    req.onsuccess = function(){
                    }   
                }
                if( (cursor.value.cantidadInicial) == (cantidadMaxima+1)){
                    //alert("No Hay mas stock");
                    cursor.stopPropagation();

                
                } 
                cursor.continue();
            } 
        }
        getCartItems();
    }




//  ------------------------------ Vaciar carrito ----------------------------------------------------------
function vaciarCarro(){ 
            var transaction = db.transaction(["carrito"], "readwrite");
            var objectStore = transaction.objectStore("carrito");
            objectStore.openCursor().onsuccess = function(event){
                var cursor = event.target.result;
                if(cursor){
                    cursor.delete();
                    cursor.continue();
                }
            }
            getCartItems();
}

// ------------------------------Procesar compra --------------------------------------------------------------


var arr=[];
function procesarCompra(){
    //var carritoInfo="";
    var aux="";
        var transaction = db.transaction(["carrito"], "readonly");
        var objectStore = transaction.objectStore("carrito");
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result;
            if(cursor){
                carritoInfo = "{nombre:" + cursor.value.name + ",precio:"+ cursor.value.price +",cantidad:"+ cursor.value.cantidadInicial+"} ";
                aux+=carritoInfo;
                //var carri = JSON.stringify(carritoInfo);
                //console.log(aux);
                //arr.push(carri)
                cursor.continue();
            }
        }
        setTimeout(() => {
            console.log(aux);
            window.location.href = `http://localhost:8000/pedido/new?array=${aux}`;
        }, 100);
}



// ------------------------------ Eliminar producto del carrito ---------------------------------------------------

    function removeFromCart(nombre) {
        var transaction = db.transaction(["carrito"], "readwrite");
        var objectStore = transaction.objectStore("carrito");
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result;
            if(cursor){
                if(cursor.value.name === nombre){                  
                    cursor.delete();
                    // console.log("eliminado");
                }
                cursor.continue();
            }
        }
        getCartItems();
    };


// ----------------------------------Ocultar y mostrar carrito---------------------------

function esconder(){
    listaCarrito.style.display="none";
}
function mostrar(){
    listaCarrito.style.display="block";
    getCartItems();


}
