# ADR-0001: Representación monetaria como float

## Status
Aceptada

## Contexto
En el requerimiento del cliente se solicitó que la información sobre precios debía guardarse en base de datos como DECIMAL(10,2), esto permite que las operaciones con decimales sean exactas porque se guarda un string del valor, no hay redondeados, es el valor exacto. Durante el desarrollo se seleccionó usar float como tipo del precio en pesos argentinos (valor en base de datos) y del precio en dólares (valor calculado).
PDO entrega el valor como string (valor guardado en base de datos) que es un valor exacto, pero se introduce imprecisión al hacer cast a float en el mapeo del repositorio PDOProductoRepository (float) $producto['precio'].

## Opciones consideradas
1. Cambiar el float por operación string + BCMath, esto permite mantener operaciones sobre string sin perder exactitud en el valor.
2. Usar un Value Object (VO) Money que maneje el tipo de moneda, y el valor.
3. [int] en centavos, exacto pero obliga a convertir en los bordes y a recodar el factor 100 en todas las partes donde este el precio, genera mas código.

## Decisión
Se decidio mantener el tipo de los precios en float. Mantener el valor en tipo float en un proyecto donde no hay operaciones que repercutan en perdidas monetarias. Hay muchos cambios, no es un cambio local, es un cambio que impacta al modelo, impacta firmas de contratos de las APIs e impacta el desarrollo en el frontend. Dejar el valor, deja intacto el desarrollo actual.

## Consecuencias

### Positivas
El desarrollo actual queda intacto, sin refactor.

### Negativas
Esta decisión se revierte cuando el precio deje de ser solo un valor a mostrar y pase a ser un valor de venta real. En términos verificables, cuando ocurra cualquiera de: (a) el precio_usd se persista en lugar de calcularse al vuelo; (b) el precio se use en una operación distinta de mostrarlo (carrito, descuento, cobro); (c) se agregue una segunda operación aritmética además de la conversión a dólares.