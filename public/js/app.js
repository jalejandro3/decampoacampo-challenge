import { listarProductos } from './api.js';

const cargando = document.getElementById('cargando');
const tabla = document.getElementById('tabla-productos');
const cuerpoTabla = document.getElementById('cuerpo-tabla');
const sinProductos = document.getElementById('sin-productos');
const mensaje = document.getElementById('mensaje');

const formateadorArs = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS'
});

const formateadorUsd = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
});

function renderizarProductos(productos) {
    cuerpoTabla.replaceChildren();

    productos.forEach(producto => {
        const fila = document.createElement('tr');
        fila.dataset.id = producto.id;

        const celdaNombre = document.createElement('td');
        celdaNombre.textContent = producto.nombre;

        const celdaDescripcion = document.createElement('td');
        celdaDescripcion.textContent = producto.descripcion;

        const celdaArs = document.createElement('td');
        celdaArs.textContent = formateadorArs.format(producto.precio);

        const celdaUsd = document.createElement('td');
        celdaUsd.textContent = formateadorUsd.format(producto.precio_usd);

        const celdaAcciones = document.createElement('td');
        // botones Actualizar/Eliminar — los cableamos en la siguiente capa

        fila.append(celdaNombre, celdaDescripcion, celdaArs, celdaUsd, celdaAcciones);
        cuerpoTabla.append(fila);
    });
}

async function cargarProductos() {
    try {
        const productos = await listarProductos();
        cargando.hidden = true;

        if (productos.length === 0) {
            sinProductos.hidden = false;
            return;
        }

        renderizarProductos(productos);
        tabla.hidden = false;
    } catch (error) {
        cargando.hidden = true;
        mensaje.textContent = error.message;
        mensaje.hidden = false;
    }
}

cargarProductos();
