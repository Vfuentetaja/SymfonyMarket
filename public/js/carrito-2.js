// Abre la conexión con la base de datos
var request = indexedDB.open("carrito", 1);
var db;
//ejecutar al cargar

var listaCarrito = document.querySelector('.listaCarrito')

// Crea el almacén de objetos si no existe
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
    objectStore.createIndex("name", "name", { unique: true });
    objectStore.createIndex("price", "price", { unique: false });
    objectStore.createIndex("quantity", "quantity", { unique: false });
};


function removeQuantity(nombre) {
    listaCarrito.innerHTML=" ";
    var transaction = db.transaction(["carrito"], "readwrite");
    var objectStore = transaction.objectStore("carrito");
    objectStore.openCursor().onsuccess = function(event){
        var cursor = event.target.result;
        if(cursor){
            if(cursor.value.name === nombre){
                var updateData = cursor.value;
                updateData.quantity-=1
                var req = cursor.update(updateData);
                req.onsuccess = function(){
                } 
                if(cursor.value.quantity===0){
                    removeFromCart(cursor.value.name);
                    window.location.href = "http://localhost:8000/producto/";
                }
            }
            cursor.continue();
            
        }
    }
    getCartItems();
}



onload = function(){
    getCartItems();
}

    // Obtiene los productos del carrito

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
                //div para elementos de cada producto
                var listaProdNombre = document.createElement('div');
                listaProdNombre.classList.add( "nombreProducto");
                listaProdNombre.innerHTML = cursor.value.name;
                var listaProdPrecio = document.createElement('div');
                listaProdPrecio.innerHTML = cursor.value.price;
                var listaProdCantidad = document.createElement('div');
                listaProdCantidad.innerHTML = cursor.value.quantity;
                // var listaProdiMAGEN = document.createElement('img');
                // listaProdiMAGEN.src=  "./public/images" + listaProdNombre + ".jpeg"
                

                total += cursor.value.price * cursor.value.quantity;
                //listaProductos.innerHTML= "Nombre "+cursor.value.name + ' Precio: ' + cursor.value.price + " Cantidad: " + cursor.value.quantity;
                //añadir a datos producto a sus tarjetas
                tarjetaProducto.appendChild(listaProdNombre);
                tarjetaProducto.appendChild(listaProdPrecio);
                tarjetaProducto.appendChild(listaProdCantidad);

                //Botonespara cada producto

                // var button = document.createElement('button'); 
                // button.classList.add("botonEliminar");
                // button.type = 'button'; 
                // button.innerText = 'Eliminar';
                tarjetaProducto.innerHTML +=`<button class="btn btn-primary" onclick="removeFromCart('${listaProdNombre.innerHTML}')">Eliminar</button>`

                //button.addEventListener(`click`, removeFromCart(listaProdNombre));
                //tarjetaProducto.appendChild(button); 
                // var button2 = document.createElement('button'); 
                // button2.classList.add("botonAñadir");
                // button2.type = 'button'; 
                // button2.innerText = '+'; 
                tarjetaProducto.innerHTML +=`<button class="btn btn-primary" onclick="addQuantity('${listaProdNombre.innerHTML}')">+</button>`
                //button2.addEventListener(`click`, addQuantity(listaProdNombre));
                // tarjetaProducto.appendChild(button2); 
                // const button3 = document.createElement('button'); 
                // button3.classList.add("botonReducir");
                // button3.type = 'button'; 
                // button3.innerText = '-'; 
                //var button3 = '<button class="btn btn-primary">-</button>'
                //button3.addEventListener(`click`, removeQuantity(listaProdNombre));
                //tarjetaProducto.innerHTML += button3;
                tarjetaProducto.innerHTML +=`<button class="btn btn-primary" onclick="removeQuantity('${listaProdNombre.innerHTML}')">-</button>`
                //tarjetaProducto.appendChild(button3); 
                cursor.continue();
                // console.log(cursor.key)
                
            }else{
                console.log("todo mostrado");
            }
        }
        setTimeout(() => {

            listaCarrito.innerHTML += `<br><div> Total: ${total}€</div>`
            listaCarrito.innerHTML += `<br><button class="btn btn-primary" onclick="vaciarCarro()" >Vaciar</button>`
            listaCarrito.innerHTML += `<br><br><button class="btn btn-primary" onclick="procesarCompra()" >Comprar</button>`
        }, 70)
    }

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

var arr=[];
function procesarCompra(){
    //var carritoInfo="";
    var aux="";
        var transaction = db.transaction(["carrito"], "readonly");
        var objectStore = transaction.objectStore("carrito");
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result;
            if(cursor){
                carritoInfo = "{nombre:" + cursor.value.name + ",precio:"+ cursor.value.price +",cantidad:"+ cursor.value.quantity+"} ";
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
    



   
    // $.ajax({
    //     type: "POST",
    //     url: "http://localhost:8000/pedido/new",
    //     data: {'array': JSON.stringify(arr)}, //pasamos array con productos del carrito  
    //     success: function(data){
    //         console.log("Exito");
    //         // $('carro').html(data);
    //         //window.location.href = "http://localhost:8000/pedido/new";
    //     },
    //     error: function(data) {
    //         console.log("ERROR");
    //     }

    // });

    // $.post('./src/Controller/PedidoController.php',{array: arr},function (data){
    //     if(data!=null){
    //         console.log("enviado");
    //     }else{
    //         console.log("no enviado");  
    //     }
    // });
}
//Mover a controller
// public function Guardar(){

//     $data = json_decode($_POST['arr']);
//     var_dump($data);

// }

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
    }

    //añadir cantidad
    function addQuantity(nombre) {
        listaCarrito.innerHTML=" ";
        var transaction = db.transaction(["carrito"], "readwrite");
        var objectStore = transaction.objectStore("carrito");
        objectStore.openCursor().onsuccess = function(event){
            var cursor = event.target.result;
            if(cursor){
                if(cursor.value.name === nombre){
                    var updateData = cursor.value;
                    updateData.quantity+=1
                    var req = cursor.update(updateData);
                    req.onsuccess = function(){
                    }         
                }
                cursor.continue();
            }
        }
        getCartItems();
    }

// Abre una transacción para operar con el almacén de objetos
request.onsuccess = function(event) {
    db = event.target.result;
    //var transaction = db.transaction("carrito", "readwrite");
    //var objectStore = transaction.objectStore("carrito");

    const addToShoppingCartButtons = document.querySelectorAll('.addToCart');
    addToShoppingCartButtons.forEach((addToCartButton) => {
    addToCartButton.addEventListener('click', addToCart);
    });

function addToCart() {
    var transaction = db.transaction("carrito", "readwrite");
    var objectStore = transaction.objectStore("carrito");
    // const itemList = document.getElementsByTagName('td');
    const itemName = document.querySelector('.productoNombre').innerText;
    console.log(itemName);
    const itemPrice = document.querySelector('.productoPrecio').innerText;
    // const itemImagen = document.querySelector('.productoImagen').innerText;
    const itemQuantity = 1;
    var item ={ name: itemName, price: itemPrice, quantity: itemQuantity };
    var request = objectStore.add(item);
    request.onsuccess = function() {
        console.log("Producto añadido al carrito.");
    };
    request.onerror = function() {
        console.log("Error producto ya esta en el carrito.");
    };
}

};