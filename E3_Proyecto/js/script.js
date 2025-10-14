// ------------------------------ Modo claro y oscuro ------------------------------

const iconoSol = document.getElementById("iconoSol");
const iconoLuna = document.getElementById("iconoLuna");

// Función para activar el modo claro
function activarClaro() {
    document.body.classList.remove("dark");
    iconoSol.classList.add("activo");
    iconoLuna.classList.remove("activo");
    localStorage.setItem("theme", "light");
    actualizarColoresGrafico();
}

// Función para activar el modo oscuro
function activarOscuro() {
    document.body.classList.add("dark");
    iconoLuna.classList.add("activo");
    iconoSol.classList.remove("activo");
    localStorage.setItem("theme", "dark");
    actualizarColoresGrafico();
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



// ------------------------------ Gráfico de actividades por prioridad ------------------------------
let chartPrioridad;

document.addEventListener("DOMContentLoaded", function () {
    fetch("../routers/graficoPrioridadRouter.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            const prioridades = ["Casual", "Normal", "Importante"];
            const colores = {
                Casual: "rgb(0, 153, 0)",
                Normal: "orange",
                Importante: "red"
            };

            const valores = { Casual: 0, Normal: 0, Importante: 0 };

            data.forEach(item => {
                if (valores.hasOwnProperty(item.prioridad)) {
                    valores[item.prioridad] = item.cantidad;
                }
            });

            const labels = prioridades;
            const cantidades = prioridades.map(p => valores[p]);
            const coloresFinales = prioridades.map(p => colores[p]);

            const ctx = document.getElementById("graficoPrioridad").getContext("2d");
            const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();

            chartPrioridad = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: labels,
                    datasets: [{
                        data: cantidades,
                        backgroundColor: coloresFinales,
                        borderColor: textoColor

                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: "bottom",
                            labels: {
                                color: textoColor,
                                font: {
                                    family: "Quicksand",
                                    size: 18
                                },
                                usePointStyle: true,
                                pointStyle: "circle",
                                padding: 25
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const dataset = context.dataset;
                                    const total = dataset.data.reduce((acc, val) => acc + val, 0);
                                    const valor = dataset.data[context.dataIndex];
                                    const porcentaje = ((valor / total) * 100).toFixed(1);
                                    return ` ${valor} (${porcentaje}%)`;
                                }
                            }
                        }
                    },
                    onHover: (event, elements) => {
                        const titulo = document.getElementById("tituloPrioridad");
                        const detalle = document.getElementById("detallePrioridad");

                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const label = labels[index];
                            const valor = cantidades[index];
                            const total = cantidades.reduce((a, b) => a + b, 0);
                            const porcentaje = ((valor / total) * 100).toFixed(1);

                            let mensaje = "";
                            if (label === "Importante") mensaje = "🔥 Estas tareas requieren foco y planificación.";
                            else if (label === "Normal") mensaje = "⚖️ Actividades regulares que mantienen tu flujo de trabajo. Gestionálas con constancia y equilibrio.";
                            else mensaje = "🌿 Tareas livianas o de bajo impacto, aprovechá para recargar energía.";

                            titulo.textContent = label;
                            detalle.textContent = `${valor} tareas (${porcentaje}%). ${mensaje}`;
                        } 
                        else {
                            titulo.textContent = "Seleccioná una prioridad";
                            detalle.textContent = "Pasá el cursor por el gráfico para ver detalles.";
                        }
                    }
                }
            });

            // -------------------- CÁLCULO DEL MENSAJE GENERAL --------------------
            const total = cantidades.reduce((a, b) => a + b, 0);
            if (total > 0) {
                const maxCount = Math.max(...cantidades);
                const indicesMax = [];
                cantidades.forEach((v, i) => { if (v === maxCount) indicesMax.push(i); });

                const mensajeGeneral = document.getElementById("mensajeGeneral");

                if (indicesMax.length === 1) {
                    // Caso único dominante
                    const prioridadDominante = labels[indicesMax[0]];
                    const porcentajeDominante = ((maxCount / total) * 100).toFixed(1);
                    let consejo = "";

                    switch (prioridadDominante) {
                        case "Importante":
                            consejo = `🔴 Esta semana tenés muchas tareas importantes (${porcentajeDominante}%). 
              Priorizá lo esencial y no te sobrecargues.`;
                            break;
                        case "Normal":
                            consejo = `🟠 La mayoría de tus tareas son normales (${porcentajeDominante}%). 
              Buen balance, mantené tu productividad sin agotarte.`;
                            break;
                        case "Casual":
                            consejo = `🟢 Semana liviana con más tareas casuales (${porcentajeDominante}%). 
              Aprovechá para organizarte, descansar o adelantar proyectos.`;
                            break;
                    }

                    mensajeGeneral.textContent = consejo;

                } else {
                    // Empate entre varias prioridades
                    const prioridadesEmpatadas = indicesMax.map(i => labels[i]);
                    const ultima = prioridadesEmpatadas.pop();
                    const conjuncion = /^[ihIh]/.test(ultima) ? "e" : "y";
                    const lista = prioridadesEmpatadas.length
                        ? `${prioridadesEmpatadas.join(", ")} ${conjuncion} ${ultima}`
                        : ultima;

                    const porcentajeEmpate = ((maxCount / total) * 100).toFixed(1);
                    let consejoEmpate = `⚖️ Tenés un empate entre ${lista} — cada una con ${maxCount} actividad(es) (${porcentajeEmpate}% del total). `;

                    if (prioridadesEmpatadas.includes("Importante") || ultima === "Importante") {
                        consejoEmpate += "Dado que incluye tareas importantes, priorizá terminar esas primero y si te sentís sobrecargado delegá o reprogramá lo que consideres necesario";
                    } else if (prioridadesEmpatadas.includes("Normal") || ultima === "Normal") {
                        consejoEmpate += "Buen equilibrio general: revisá si podés optimizar o agrupar tareas para ahorrar tiempo.";
                    } else {
                        consejoEmpate += "Semana tranquila: aprovechá para descansar, planificar o avanzar en tareas a largo plazo.";
                    }

                    mensajeGeneral.textContent = consejoEmpate;
                }

                document.getElementById("resumenGeneral").classList.add("visible");
            } else {
                document.getElementById("mensajeGeneral").textContent =
                    "No hay actividades registradas aún.";
            }
        })
        .catch(error => console.error("Error en fetch:", error));
});

// ------------------------------ Actualizar colores al cambiar de modo ------------------------------
function actualizarColoresGrafico() {
    if (!chartPrioridad) return;

    const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();
    chartPrioridad.options.plugins.legend.labels.color = textoColor;
    chartPrioridad.data.datasets.forEach((dataset) => {
    dataset.borderColor = textoColor;})
    chartPrioridad.update();
}
