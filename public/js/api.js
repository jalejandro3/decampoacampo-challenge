export async function listarProductos() {
    const respuesta = await fetch('/productos');

    if (!respuesta.ok) {
        throw new Error('No se pudieron cargar los productos');
    }

    return await respuesta.json();
}

export async function crearProducto(datos) {
    const respuesta = await fetch('/productos', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    });

    if (!respuesta.ok) {
        const error = await respuesta.json();
        throw new Error(error.error);
    }

    return await respuesta.json();
}
