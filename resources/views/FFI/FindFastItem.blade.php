<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Búsqueda de Ítems</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input {
            width: 80%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #loading {
            display: none;
            margin-top: 10px;
            font-size: 14px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Buscar Ítem</h2>
    <input type="text" id="searchInput" placeholder="Ingrese el código del ítem">
    <button id="searchButton">Buscar</button>
    <p id="loading">Buscando...</p>
    <p id="result"></p>
    <div id="result-container"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");
        const resultElement = document.getElementById("result");
        const loadingElement = document.getElementById("loading");

        function searchItem() {
            let code = searchInput.value.trim();

            resultElement.textContent = "";
            loadingElement.style.display = "block";

            if (code === "") {
                resultElement.textContent = "Por favor, ingrese un código.";
                loadingElement.style.display = "none";
                return;
            }

            fetch(`/api/search-item/${encodeURIComponent(code)}`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('result-container');
                    container.innerHTML = ''; // limpiar resultados previos

                    if (data.data) {
                        const item = data.data;

                        const table = document.createElement('table');
                        table.border = '1';
                        table.style.borderCollapse = 'collapse';
                        table.style.width = '100%';

                        table.innerHTML = `
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Ubicación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.item_code}</td>
                        <td>${item.name}</td>
                        <td>${item.location_id}</td>
                        <td><a href="items/${item.id}/show" target="_blank">VER</a></td>
                    </tr>
                </tbody>
            `;

                        container.appendChild(table);
                    } else {
                        container.textContent = 'Ítem no encontrado.';
                    }
                })
                .catch(error => {
                    console.error("Error en la búsqueda:", error);
                    resultElement.textContent = "Hubo un problema en la búsqueda.";
                })
                .finally(() => {
                    loadingElement.style.display = "none";
                });
        }

        searchButton.addEventListener("click", searchItem);
        searchInput.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                searchItem();
            }
        });
    });
</script>

</body>
</html>
