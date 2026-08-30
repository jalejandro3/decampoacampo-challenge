async function pedir (url, options = {}) {
    let respuesta;
    try {
        respuesta = await fetch(url, options);
    } catch (error) {
        throw new Error('No se pudo conectar con el servidor');
    }
    if (respuesta.status === 204) {
        return null;
    }
    const cuerpo = await respuesta.json();
    if (!respuesta.ok) {
        throw new Error(cuerpo.error);
    }
    return cuerpo;
}

function peticionJson(metodo, datos) {
    return {
        method: metodo,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    };
}

export const listarProductos = () => pedir('/productos');
export const obtenerProducto = (id) => pedir(`/productos/${id}`);
export const crearProducto = (datos) => pedir('/productos', peticionJson('POST', datos));
export const actualizarProducto = (id, datos) => pedir(`/productos/${id}`, peticionJson('PUT', datos));
export const eliminarProducto = (id) => pedir(`/productos/${id}`, { method: 'DELETE' });
