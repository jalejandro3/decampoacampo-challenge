import { elementosLista } from './dom.js';

const { cuerpoTabla } = elementosLista;

const formateadorArs = new Intl.NumberFormat('es-AR', {style: 'currency', currency: 'ARS'});
const formateadorUsd = new Intl.NumberFormat('en-US', {style: 'currency', currency: 'USD'});

function crearFilaProducto(producto) {
    const fila = document.createElement('tr');
    fila.className = 'tabla-productos__fila';
    fila.dataset.id = producto.id;

    const celdaNombre = document.createElement('td');
    celdaNombre.className = 'tabla-productos__celda';
    celdaNombre.textContent = producto.nombre;

    const celdaDescripcion = document.createElement('td');
    celdaDescripcion.className = 'tabla-productos__celda';
    celdaDescripcion.textContent = producto.descripcion;

    const celdaArs = document.createElement('td');
    celdaArs.className = 'tabla-productos__celda';
    celdaArs.textContent = formateadorArs.format(producto.precio);

    const celdaUsd = document.createElement('td');
    celdaUsd.className = 'tabla-productos__celda';
    celdaUsd.textContent = formateadorUsd.format(producto.precio_usd);

    const btnEditar = document.createElement('button');
    btnEditar.textContent = 'Actualizar';
    btnEditar.className = 'btn-editar boton';
    btnEditar.dataset.id = producto.id;

    const btnEliminar = document.createElement('button');
    btnEliminar.textContent = 'Eliminar';
    btnEliminar.className = 'btn-eliminar boton boton--peligro';
    btnEliminar.dataset.id = producto.id;
    btnEliminar.dataset.nombre = producto.nombre;

    const celdaAcciones = document.createElement('td');
    celdaAcciones.className = 'tabla-productos__acciones';
    celdaAcciones.append(btnEditar, btnEliminar);

    fila.append(celdaNombre, celdaDescripcion, celdaArs, celdaUsd, celdaAcciones);
    return fila;
}

export function renderizarProductos(productos) {
    cuerpoTabla.replaceChildren();
    productos.forEach(producto => cuerpoTabla.append(crearFilaProducto(producto)));
}
