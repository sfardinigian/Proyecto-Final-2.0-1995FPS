// ------------------------------ Modo claro y oscuro ------------------------------

const iconoSol = document.getElementById("iconoSol");
const iconoLuna = document.getElementById("iconoLuna");

// Función para activar el modo claro
function activarClaro() {
    document.body.classList.remove("dark");
    iconoSol.classList.add("activo");
    iconoLuna.classList.remove("activo");
    localStorage.setItem("theme", "light");
}

// Función para activar el modo oscuro
function activarOscuro() {
    document.body.classList.add("dark");
    iconoLuna.classList.add("activo");
    iconoSol.classList.remove("activo");
    localStorage.setItem("theme", "dark");
}

// Eventos al tocar los botones
iconoSol.addEventListener("click", activarClaro);
iconoLuna.addEventListener("click", activarOscuro);

// Para que quede guardado el modo en el navegador
document.addEventListener("DOMContentLoaded", () => {
    if (localStorage.getItem("theme") === "dark") {
        activarOscuro();
    }
    else {
        activarClaro();
    }
});

// ------------------------------ Mostrar y ocultar datos de cuenta ------------------------------

// Mostrar/ocultar menú al hacer click
document.addEventListener("DOMContentLoaded", () => {
    const cuentaBoton = document.getElementById("cuentaBoton");
    const cuentaDatos = document.getElementById("cuentaDatos");
    const cerrarMenu = document.getElementById("cerrarMenu");

    if (cuentaBoton && cuentaDatos) {
        cuentaBoton.addEventListener("click", (e) => {
            e.stopPropagation();
            cuentaDatos.classList.toggle("activo");
        });

        // Para que se cierre al tocar la "x"
        cerrarMenu.addEventListener("click", () => {
            cuentaDatos.classList.remove("activo");
        });

        document.addEventListener("click", (e) => {
            if (!cuentaDatos.contains(e.target) && !cuentaBoton.contains(e.target)) {
                cuentaDatos.classList.remove("activo");
            }
        });
    }
});

// ------------------------------ Gráfico de promedio de prioridades ------------------------------

document.addEventListener("DOMContentLoaded", function () {
    fetch("../routers/graficoPrioridadRouter.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            // Definir prioridades fijas y sus colores
            const prioridades = ["Casual", "Normal", "Importante"];
            const colores = {
                Casual: "rgb(0, 153, 0)",
                Normal: "orange",
                Importante: "red"
            };

            // Inicializar cantidades en 0
            const valores = {
                Casual: 0,
                Normal: 0,
                Importante: 0
            };

            // Rellenar valores según el JSON
            data.forEach(item => {
                if (valores.hasOwnProperty(item.prioridad)) {
                    valores[item.prioridad] = item.cantidad;
                }
            });

            // Preparar arrays para Chart.js
            const labels = prioridades;
            const cantidades = prioridades.map(p => valores[p]);
            const coloresFinales = prioridades.map(p => colores[p]);

            // Crear gráfico tipo pie
            const ctx = document.getElementById("graficoPrioridad").getContext("2d");
            new Chart(ctx, {
                type: "pie",   // 👈 cambiás aquí
                data: {
                    labels: labels,
                    datasets: [{
                        data: cantidades,
                        backgroundColor: coloresFinales
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true, // mantiene proporción circular
                    plugins: {
                        legend: {
                            display: true,   // en pie sí conviene mostrar
                            position: "bottom"
                        },
                        title: {
                            display: true,
                            text: "Actividades agrupadas por prioridad"
                        }
                    }
                }
            });
        })
        .catch(error => console.error("Error en fetch:", error));
});
