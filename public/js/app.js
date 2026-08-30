import { listarProductos, crearProducto } from './api.js';

const cargando = document.getElementById('cargando');
const tabla = document.getElementById('tabla-productos');
const cuerpoTabla = document.getElementById('cuerpo-tabla');
const sinProductos = document.getElementById('sin-productos');
const mensaje = document.getElementById('mensaje');
const btnNuevo = document.getElementById('btn-nuevo');
const form = document.getElementById('form-producto');
const btnCancelar = document.getElementById('btn-cancelar');
const inputNombre = document.getElementById('nombre');
const inputDescripcion = document.getElementById('descripcion');
const inputPrecio = document.getElementById('precio');

const formateadorArs = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS'
});

const formateadorUsd = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
});

function mostrarMensaje(texto) {
    mensaje.textContent = texto;
    mensaje.hidden = false;
}

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
            tabla.hidden = true;
            sinProductos.hidden = false;
            return;
        }

        renderizarProductos(productos);
        sinProductos.hidden = true;
        tabla.hidden = false;
    } catch (error) {
        cargando.hidden = true;
        mostrarMensaje(error.message);
    }
}

btnNuevo.addEventListener('click', () => {
    form.reset();
    mensaje.hidden = true;
    form.hidden = false;
    inputNombre.focus();
});

btnCancelar.addEventListener('click', () => {
    form.hidden = true;
});

form.addEventListener('submit', async (evento) => {
    evento.preventDefault();

    const datos = {
        nombre: inputNombre.value.trim(),
        descripcion: inputDescripcion.value.trim(),
        precio: Number(inputPrecio.value)
    };

    try {
        await crearProducto(datos);
        form.hidden = true;
        await cargarProductos();
        mostrarMensaje('Producto creado correctamente.');
    } catch (error) {
        mostrarMensaje(error.message);
    }
});

cargarProductos();
