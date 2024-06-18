<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error { color: red; }
    </style>
</head>
<body class="bg-gray-100 p-6">

<div class="container mx-auto">
    <h2 class="text-2xl font-bold mb-4">Formulario de Productos</h2>

    <?php
    // Inicializamos variables y mensajes de error
    $nombreProducto = $precioPorUnidad = $cantidadInventario = "";
    $nombreErr = $precioErr = $cantidadErr = "";
    $productos = [];

    // Función para limpiar los datos ingresados
    function test_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Función para mostrar el estado del producto
    function mostrarEstado($cantidad) {
        return $cantidad == 0 ? "Agotado" : "En stock";
    }

    // Función para agregar un producto al array asociativo
    function agregarProducto(&$productos, $nombre, $precio, $cantidad) {
        $productos[] = [
            "nombre" => $nombre,
            "precio" => $precio,
            "cantidad" => $cantidad
        ];
    }

    // Función para mostrar la tabla de productos
    function mostrarProductos($productos) {
        echo '<div class="overflow-x-auto">';
        echo '<table class="min-w-full bg-white">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="py-2 px-4 border-b">Nombre del Producto</th>';
        echo '<th class="py-2 px-4 border-b">Precio por Unidad</th>';
        echo '<th class="py-2 px-4 border-b">Cantidad en Inventario</th>';
        echo '<th class="py-2 px-4 border-b">Valor Total</th>';
        echo '<th class="py-2 px-4 border-b">Estado</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($productos as $producto) {
            $valorTotal = $producto["precio"] * $producto["cantidad"];
            $estado = mostrarEstado($producto["cantidad"]);
            echo '<tr>';
            echo '<td class="py-2 px-4 border-b">' . $producto["nombre"] . '</td>';
            echo '<td class="py-2 px-4 border-b">' . $producto["precio"] . '</td>';
            echo '<td class="py-2 px-4 border-b">' . $producto["cantidad"] . '</td>';
            echo '<td class="py-2 px-4 border-b">' . $valorTotal . '</td>';
            echo '<td class="py-2 px-4 border-b">' . $estado . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    }

    // Validamos y procesamos el formulario cuando se envía
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $valid = true;

        if (empty($_POST["nombre"])) {
            $nombreErr = "El nombre del producto es obligatorio.";
            $valid = false;
        } else {
            $nombreProducto = test_input($_POST["nombre"]);
        }

        if (empty($_POST["precio"])) {
            $precioErr = "El precio por unidad es obligatorio.";
            $valid = false;
        } elseif (!is_numeric($_POST["precio"]) || $_POST["precio"] <= 0) {
            $precioErr = "El precio por unidad debe ser un número positivo.";
            $valid = false;
        } else {
            $precioPorUnidad = test_input($_POST["precio"]);
        }

        if (empty($_POST["cantidad"])) {
            $cantidadErr = "La cantidad en inventario es obligatoria.";
            $valid = false;
        } elseif (!is_numeric($_POST["cantidad"]) || $_POST["cantidad"] < 0) {
            $cantidadErr = "La cantidad en inventario debe ser un número no negativo.";
            $valid = false;
        } else {
            $cantidadInventario = test_input($_POST["cantidad"]);
        }

        if ($valid) {
            agregarProducto($productos, $nombreProducto, $precioPorUnidad, $cantidadInventario);
        }
    }
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">
                Nombre del producto:
            </label>
            <input type="text" name="nombre" value="<?php echo $nombreProducto;?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <span class="error"><?php echo $nombreErr;?></span>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
                Precio por unidad:
            </label>
            <input type="text" name="precio" value="<?php echo $precioPorUnidad;?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <span class="error"><?php echo $precioErr;?></span>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="cantidad">
                Cantidad en inventario:
            </label>
            <input type="text" name="cantidad" value="<?php echo $cantidadInventario;?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <span class="error"><?php echo $cantidadErr;?></span>
        </div>
        <div class="flex items-center justify-between">
            <input type="submit" name="submit" value="Agregar Producto" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        </div>
    </form>

    <h2 class="text-2xl font-bold mb-4">Lista de Productos</h2>

    <?php
    if (!empty($productos)) {
        mostrarProductos($productos);
    }
    ?>

</div>

</body>
</html>

