export async function listarProductos() {
    const respuesta = await fetch('/productos');

    if (!respuesta.ok) {
        throw new Error('No se pudieron cargar los productos');
    }

    return await respuesta.json();
}
