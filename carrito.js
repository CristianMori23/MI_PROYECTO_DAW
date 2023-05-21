function agregarAlCarrito(id, nombre, talla, precio) {
    // Eliminar el símbolo del euro y convertir el precio a un número
    const precioNumerico = parseFloat(precio.replace('€', ''));

    let carrito = sessionStorage.getItem("carrito");

    if (carrito === null) {
        carrito = [];
    } else {
        carrito = JSON.parse(carrito);
    }

    let itemEncontrado = false;

    for (let i = 0; i < carrito.length; i++) {
        if (carrito[i].id === id) {
            carrito[i].cantidad++;
            itemEncontrado = true;
            break;
        }
    }

    if (!itemEncontrado) {
        let nuevoItem = {
            id: id,
            nombre: nombre,
            talla: talla, 
            precio: precioNumerico,
            cantidad: 1,
        };
        carrito.push(nuevoItem);
    }

    sessionStorage.setItem('carrito', JSON.stringify(carrito));
    actualizarContadorCarrito();
    mostrarCarrito();
}
function actualizarContadorCarrito() {
    let carrito = sessionStorage.getItem("carrito");

    if (carrito === null) {
        carrito = [];
    } else {
        carrito = JSON.parse(carrito);
    }

    let contador = 0;
    carrito.forEach(producto => {
        contador += producto.cantidad;
    });

    let carritoContador = document.getElementById("carrito-contador");
    carritoContador.textContent = "(" + contador + ")";

    // Aquí agregamos el código para actualizar el número en la barra de navegación
    let carritoNavbar = document.getElementById("carrito-dropdown");
    carritoNavbar.textContent = ""; // Limpiar el contenido actual

    carrito.forEach(producto => {
        let itemNavbar = document.createElement("div");
        itemNavbar.className = "carrito-dropdown__item";
        itemNavbar.textContent = `${producto.nombre} - ${producto.cantidad} x ${producto.precio}€`;
        carritoNavbar.appendChild(itemNavbar);
    });
  
}


function mostrarCarrito() {
    let carrito = sessionStorage.getItem("carrito");

    if (carrito === null) {
        carrito = [];
    } else {
        carrito = JSON.parse(carrito);
    }

    let carritoProductos = document.getElementById("carrito-productos");
    carritoProductos.innerHTML = "";

    let carritoDropdown = document.getElementById("carrito-dropdown");
    carritoDropdown.innerHTML = "";

    let total = 0;

    carrito.forEach(producto => {
        total += producto.precio * producto.cantidad;

        let fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${producto.nombre}</td>
            <td>${producto.talla}</td>
            <td>${producto.precio}€</td>
            <td>${producto.cantidad}</td>
            <td>${(producto.precio * producto.cantidad).toFixed(2)}€</td>
            <td><button onclick="eliminarDelCarrito('${producto.id}')">Eliminar</button></td>
        `;

        carritoProductos.appendChild(fila);

        let itemDropdown = document.createElement("div");
        itemDropdown.className = "carrito-dropdown__item";
        itemDropdown.textContent = `${producto.nombre} - ${producto.cantidad} x ${producto.precio}€`;
        carritoDropdown.appendChild(itemDropdown);
    });

    let carritoTotal = document.getElementById("carrito-total");
    carritoTotal.textContent = total.toFixed(2) + "€";
}
function eliminarDelCarrito(id) {
    let carrito = sessionStorage.getItem("carrito");

    if (carrito === null) {
        return;
    } else {
        carrito = JSON.parse(carrito);
    }

    for (let i = 0; i < carrito.length; i++) {
        if (carrito[i].id == id) {
            carrito.splice(i, 1);
            break;
        }
    }

    sessionStorage.setItem("carrito", JSON.stringify(carrito));
    actualizarContadorCarrito();
    mostrarCarrito();
}



function vaciarCarrito() {
    sessionStorage.removeItem("carrito");
    // actualizarContadorCarrito();
    // mostrarCarrito();
}

document.addEventListener("DOMContentLoaded", function () {
   
    mostrarCarrito();
    actualizarContadorCarrito();
});




