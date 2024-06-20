<!DOCTYPE html>
<html>
<head>
    <title>Operaciones con Matrices</title>
    <style>
        body {
            background-color: #000000;
            color: #FFFFFF;
            font-family: Arial, sans-serif;
        }
        form {
            margin: 20px;
        }
        label, select, input, textarea {
            display: block;
            margin-bottom: 10px;
        }
        textarea, input, select {
            width: 100%;
            padding: 10px;
            background-color: #333333;
            border: 1px solid #555555;
            color: #FFFFFF;
        }
        input[type="submit"] {
            background-color: #0055FF;
            border: none;
            padding: 10px 20px;
            color: #FFFFFF;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0033CC;
        }
        h2 {
            color: #00AAFF;
        }
        pre {
            background-color: #111111;
            padding: 10px;
            color: #00AAFF;
        }
    </style>
</head>
<body>
    <h1>Operaciones con Matrices</h1>
    <form method="post">
        <label for="matriz1">Matriz 1 (Formato: [1,2,3],[4,5,6],[7,8,9]):</label>
        <textarea name="matriz1" id="matriz1" rows="5" cols="40" placeholder="[1,2,3],[4,5,6],[7,8,9]"></textarea>
        <label for="matriz2">Matriz 2 (Formato: [9,8,7],[6,5,4],[3,2,1]):</label>
        <textarea name="matriz2" id="matriz2" rows="5" cols="40" placeholder="[9,8,7],[6,5,4],[3,2,1]"></textarea>
        <label for="escalar">Escalar:</label>
        <input type="text" name="escalar" id="escalar" placeholder="Por ejemplo: 2">
        <label for="operacion">Operación:</label>
        <select name="operacion" id="operacion">
            <option value="fusionar">Fusionar Matrices</option>
            <option value="producto">Producto por Escalar</option>
            <option value="traspuesta">Matriz Traspuesta</option>
            <option value="inversa">Matriz Inversa</option>
        </select>
        <input type="submit" value="Calcular">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Función para convertir una cadena de texto en una matriz PHP
        function parseMatriz($str) {
            $str = str_replace(['[', ']'], '', $str); // Elimina los corchetes
            $rows = explode('],[', $str); // Divide la cadena en filas
            $matriz = [];
            foreach ($rows as $row) {
                $matriz[] = array_map('intval', explode(',', $row)); // Convierte cada fila en un array de enteros
            }
            return $matriz;
        }

        // Función para fusionar dos matrices
        function cargarMatrices($matriz1, $matriz2) {
            return array_merge($matriz1, $matriz2); // Fusiona las matrices
        }

        // Función para calcular el producto de una matriz por un escalar
        function productoPorEscalar($matriz, $escalar) {
            $resultado = [];
            foreach ($matriz as $fila) {
                $nuevaFila = [];
                foreach ($fila as $valor) {
                    $nuevaFila[] = $valor * $escalar; // Multiplica cada elemento por el escalar
                }
                $resultado[] = $nuevaFila;
            }
            return $resultado;
        }

        // Función para calcular la matriz traspuesta
        function matrizTraspuesta($matriz) {
            $traspuesta = [];
            for ($i = 0; $i < count($matriz); $i++) {
                for ($j = 0; $j < count($matriz[0]); $j++) {
                    $traspuesta[$j][$i] = $matriz[$i][$j]; // Intercambia filas por columnas
                }
            }
            return $traspuesta;
        }

        // Función para calcular la matriz inversa (solo matrices 3x3 para este ejemplo)
        function matrizInversa($matriz) {
            if (count($matriz) != 3 || count($matriz[0]) != 3) {
                return null; // Solo soportamos matrices 3x3 para este ejemplo
            }
            // Calcula el determinante de la matriz 3x3
            $determinante = $matriz[0][0] * ($matriz[1][1] * $matriz[2][2] - $matriz[1][2] * $matriz[2][1]) -
                            $matriz[0][1] * ($matriz[1][0] * $matriz[2][2] - $matriz[1][2] * $matriz[2][0]) +
                            $matriz[0][2] * ($matriz[1][0] * $matriz[2][1] - $matriz[1][1] * $matriz[2][0]);
            if ($determinante == 0) {
                return null; // La matriz no tiene inversa
            }
            // Calcula la matriz adjunta y divide por el determinante
            $adjunta = [
                [
                    ($matriz[1][1] * $matriz[2][2] - $matriz[1][2] * $matriz[2][1]) / $determinante,
                    ($matriz[0][2] * $matriz[2][1] - $matriz[0][1] * $matriz[2][2]) / $determinante,
                    ($matriz[0][1] * $matriz[1][2] - $matriz[0][2] * $matriz[1][1]) / $determinante
                ],
                [
                    ($matriz[1][2] * $matriz[2][0] - $matriz[1][0] * $matriz[2][2]) / $determinante,
                    ($matriz[0][0] * $matriz[2][2] - $matriz[0][2] * $matriz[2][0]) / $determinante,
                    ($matriz[0][2] * $matriz[1][0] - $matriz[0][0] * $matriz[1][2]) / $determinante
                ],
                [
                    ($matriz[1][0] * $matriz[2][1] - $matriz[1][1] * $matriz[2][0]) / $determinante,
                    ($matriz[0][1] * $matriz[2][0] - $matriz[0][0] * $matriz[2][1]) / $determinante,
                    ($matriz[0][0] * $matriz[1][1] - $matriz[0][1] * $matriz[1][0]) / $determinante
                ]
            ];
            return matrizTraspuesta($adjunta); // Devuelve la matriz traspuesta de la adjunta
        }

        // Procesamiento del formulario
        $matriz1 = parseMatriz($_POST['matriz1']);
        $matriz2 = isset($_POST['matriz2']) ? parseMatriz($_POST['matriz2']) : [];
        $escalar = isset($_POST['escalar']) ? intval($_POST['escalar']) : 0;
        $operacion = $_POST['operacion'];

        // Ejecuta la operación seleccionada
        $resultado = null;
        if ($operacion == 'fusionar') {
            $resultado = cargarMatrices($matriz1, $matriz2);
        } elseif ($operacion == 'producto') {
            $resultado = productoPorEscalar($matriz1, $escalar);
        } elseif ($operacion == 'traspuesta') {
            $resultado = matrizTraspuesta($matriz1);
        } elseif ($operacion == 'inversa') {
            $resultado = matrizInversa($matriz1);
        }

        // Muestra el resultado
        echo "<h2>Resultado:</h2><pre>";
        if ($resultado !== null) {
            foreach ($resultado as $fila) {
                echo "[ " . implode(", ", $fila) . " ]<br>";
            }
        } else {
            echo "No se pudo calcular el resultado.";
        }
echo "</pre>";
    }
    ?>
</body>
</html>