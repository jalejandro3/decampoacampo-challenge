import { listarProductos, crearProducto, eliminarProducto, obtenerProducto, actualizarProducto } from './api.js';
import { elementosMensaje, elementosLista, elementosFormulario, elementosModal } from './dom.js';
import { renderizarProductos } from './render.js';

const { mensaje } = elementosMensaje;
const { cargando, tabla, cuerpoTabla, sinProductos } = elementosLista;
const { btnNuevo, form, btnCancelar, btnGuardar, tituloForm, inputNombre, inputDescripcion, inputPrecio } = elementosFormulario;
const { modalEliminar, textoConfirmar } = elementosModal;

let modoEdicion = null;
let temporizadorMensaje = null;

function abrirFormulario(titulo, id, datos = { nombre: '', descripcion: '', precio: '' }) {
    inputNombre.value = datos.nombre;
    inputDescripcion.value = datos.descripcion;
    inputPrecio.value = datos.precio;
    modoEdicion = id;
    tituloForm.textContent = titulo;
    mensaje.hidden = true;
    form.hidden = false;
    btnNuevo.hidden = true;
    inputNombre.focus();
}

function mostrarMensaje(texto, tipo) {
    mensaje.textContent = texto;
    mensaje.className = `mensaje mensaje--${tipo}`;
    mensaje.hidden = false;

    clearTimeout(temporizadorMensaje);
    temporizadorMensaje = setTimeout(() => {
        mensaje.hidden = true;
    }, 2000);
}

function confirmarEliminacion(nombre) {
    textoConfirmar.textContent = `¿Seguro quieres eliminar "${nombre}"?`;
    modalEliminar.showModal();
    return new Promise(resolve => {
        modalEliminar.addEventListener('close', () => {
            resolve(modalEliminar.returnValue === 'confirmar');
        }, { once: true });
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
        mostrarMensaje(error.message, 'error');
    }
}

async function eliminarDesdeTabla(id, nombre) {
    const confirmado = await confirmarEliminacion(nombre);
    if (!confirmado) return;

    try {
        await eliminarProducto(id);
        await cargarProductos();
        mostrarMensaje('Producto eliminado correctamente.', 'exito');
    } catch (error) {
        mostrarMensaje(error.message, 'error');
    }
}

async function editarDesdeTabla(id) {
    try {
        const producto = await obtenerProducto(id);
        abrirFormulario('Editar producto', id, producto);
    } catch (error) {
        mostrarMensaje(error.message, 'error');
        await cargarProductos();
    }
}

btnNuevo.addEventListener('click', () => abrirFormulario('Nuevo producto', null));
btnCancelar.addEventListener('click', () => {
    form.hidden = true;
    btnNuevo.hidden = false;
});
btnGuardar.addEventListener('click', () => {
    btnNuevo.hidden = false;
});

cuerpoTabla.addEventListener('click', async (evento) => {
    const boton = evento.target;
    if (boton.classList.contains('btn-eliminar')) {
        eliminarDesdeTabla(boton.dataset.id, boton.dataset.nombre);
    }
    if (boton.classList.contains('btn-editar')) {
        editarDesdeTabla(boton.dataset.id);
    }
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
            mostrarMensaje('Producto creado correctamente.', 'exito');
        } else {
            await actualizarProducto(modoEdicion, datos);
            mostrarMensaje('Producto actualizado correctamente.', 'exito');
        }
        form.hidden = true;
        await cargarProductos();
    } catch (error) {
        mostrarMensaje(error.message, 'error');
    }
});

cargarProductos();
