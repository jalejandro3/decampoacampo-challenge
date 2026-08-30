import { listarProductos, crearProducto, eliminarProducto, obtenerProducto, actualizarProducto } from './api.js';
import { elementosMensaje, elementosLista, elementosFormulario, elementosModal } from './dom/dom.js';

const { mensaje } = elementosMensaje;
const { cargando, tabla, cuerpoTabla, sinProductos } = elementosLista;
const { btnNuevo, form, btnCancelar, tituloForm, inputNombre, inputDescripcion, inputPrecio } = elementosFormulario;
const { modalEliminar, textoConfirmar } = elementosModal;

let modoEdicion = null;

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

function confirmarEliminacion(nombre) {
    textoConfirmar.textContent = `¿Eliminar "${nombre}"?`;
    modalEliminar.showModal();
    return new Promise(resolve => {
        modalEliminar.addEventListener('close', () => {
            resolve(modalEliminar.returnValue === 'confirmar');
        }, { once: true });
    });
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

        const btnEditar = document.createElement('button');
        btnEditar.textContent = 'Actualizar';
        btnEditar.className = 'btn-editar';
        btnEditar.dataset.id = producto.id;

        const btnEliminar = document.createElement('button');
        btnEliminar.textContent = 'Eliminar';
        btnEliminar.className = 'btn-eliminar peligro';
        btnEliminar.dataset.id = producto.id;
        btnEliminar.dataset.nombre = producto.nombre;

        celdaAcciones.append(btnEditar, btnEliminar);

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

cuerpoTabla.addEventListener('click', async (evento) => {
    const boton = evento.target;

    if (boton.classList.contains('btn-eliminar')) {
        const id = boton.dataset.id;
        const nombre = boton.dataset.nombre;

        const confirmado = await confirmarEliminacion(nombre);
        if (!confirmado) return;

        try {
            await eliminarProducto(id);
            await cargarProductos();
            mostrarMensaje('Producto eliminado correctamente.');
        } catch (error) {
            mostrarMensaje(error.message);
        }
    }

    if (boton.classList.contains('btn-editar')) {
        const id = boton.dataset.id;

        try {
            const producto = await obtenerProducto(id);   // GET fresco (D2)
            inputNombre.value = producto.nombre;
            inputDescripcion.value = producto.descripcion;
            inputPrecio.value = producto.precio;
            modoEdicion = id;
            tituloForm.textContent = 'Editar producto';
            mensaje.hidden = true;
            form.hidden = false;
            inputNombre.focus();
        } catch (error) {
            mostrarMensaje(error.message);
            await cargarProductos();
        }
    }
});

btnNuevo.addEventListener('click', () => {
    form.reset();
    modoEdicion = null;
    tituloForm.textContent = 'Nuevo producto';
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
        if (modoEdicion === null) {
            await crearProducto(datos);
            mostrarMensaje('Producto creado correctamente.');
        } else {
            await actualizarProducto(modoEdicion, datos);
            mostrarMensaje('Producto actualizado correctamente.');
        }
        form.hidden = true;
        await cargarProductos();
    } catch (error) {
        mostrarMensaje(error.message);
    }
});

cargarProductos();
