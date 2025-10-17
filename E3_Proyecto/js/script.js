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

            const total = cantidades.reduce((a, b) => a + b, 0);
            if (total > 0) {
                const maxCount = Math.max(...cantidades);
                const indicesMax = [];
                cantidades.forEach((v, i) => { if (v === maxCount) indicesMax.push(i); });

                const mensajeGeneral = document.getElementById("mensajeGeneral");

                if (indicesMax.length === 1) {
                    // Caso si una prioridad domina
                    const prioridadDominante = labels[indicesMax[0]];
                    const porcentajeDominante = ((maxCount / total) * 100).toFixed(1);
                    let consejo = "";

                    switch (prioridadDominante) {
                        case "Importante":
                            consejo = `🔴 Esta semana tenés muchas tareas importantes (${porcentajeDominante}%). Priorizá lo esencial y no te sobrecargues.`;
                            break;
                        case "Normal":
                            consejo = `🟠 La mayoría de tus tareas son normales (${porcentajeDominante}%). Buen balance, mantené tu productividad sin agotarte.`;
                            break;
                        case "Casual":
                            consejo = `🟢 Semana liviana con más tareas casuales (${porcentajeDominante}%). Aprovechá para organizarte, descansar o adelantar proyectos.`;
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

// ------------------------------ Generar gráfico semanal ------------------------------

let chartSemanal;

document.addEventListener("DOMContentLoaded", function () {
    fetch("../routers/graficoSemanalRouter.php")
        .then(response => response.json())
        .then(data => {
            if (!data || data.message) {
                console.warn("Sin datos semanales");
                return;
            }

            const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();
            const diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

            const convertirHora = (hora) => {
                const [h, m] = hora.split(":").map(Number);
                return h + m / 60;
            };

            const actividadesProcesadas = data.map((act) => ({
                x: act.dia,
                y: [convertirHora(act.hora_inicio), convertirHora(act.hora_fin)],
                titulo: act.titulo,
                color: act.color || "#999"
            }));

            const ctx = document.getElementById("graficoSemanal").getContext("2d");

            chartSemanal = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: diasSemana,
                    datasets: [
                        {
                            label: "Actividades",
                            data: actividadesProcesadas,
                            backgroundColor: actividadesProcesadas.map(a => a.color),
                            borderWidth: 0
                        }
                    ]
                },
                options: {
                    indexAxis: "x",
                    parsing: {
                        xAxisKey: "x",
                        yAxisKey: "y"
                    },
                    scales: {
                        x: {
                            stacked: true,
                            title: { display: true, text: "Día de la semana", color: textoColor, font: { family: "Quicksand", size: 16 } },
                            ticks: {
                                color: textoColor, font: {
                                    family: "Quicksand",
                                    size: 18, padding: 10
                                },
                            },
                            grid: {
                                color: "rgba(150, 150, 150, 0.4)",
                                lineWidth: 1
                            }
                        },
                        y: {
                            stacked: true,
                            title: { display: true, text: "Hora del día", color: textoColor, font: { family: "Quicksand", size: 16 } },
                            min: 0,
                            max: 24,
                            ticks: {
                                stepSize: 2,
                                color: textoColor,
                                font: { family: "Quicksand", size: 12 },
                                callback: value => {
                                    if (value === 24) return "00:00";
                                    return `${value}:00`;
                                }
                            },
                            grid: {
                                color: "rgba(150, 150, 150, 0.4)",
                                lineWidth: 1
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const act = context.raw;
                                    if (!act) return null;

                                    const [inicio, fin] = act.y;

                                    const formatoHora = (valor) => {
                                        const horas = Math.floor(valor);
                                        const minutos = Math.round((valor - horas) * 60);
                                        return `${String(horas).padStart(2, "0")}:${String(minutos).padStart(2, "0")}`;
                                    };

                                    return `${act.titulo} (${formatoHora(inicio)} - ${formatoHora(fin)})`;
                                }
                            }
                        }
                    }
                }
            });

            const contenedorLeyenda = document.getElementById("leyendaSemanal");
            contenedorLeyenda.innerHTML = "";

            const actividadesUnicas = [];
            data.forEach(a => {
                if (!actividadesUnicas.some(x => x.titulo === a.titulo)) {
                    actividadesUnicas.push(a);
                }
            });

            actividadesUnicas.forEach(a => {
                const item = document.createElement("div");
                item.style.display = "flex";
                item.style.alignItems = "center";
                item.style.marginBottom = "5px";

                const colorBox = document.createElement("span");
                colorBox.style.width = "16px";
                colorBox.style.height = "16px";
                colorBox.style.borderRadius = "50%";
                colorBox.style.display = "inline-block";
                colorBox.style.marginRight = "8px";
                colorBox.style.backgroundColor = a.color || "#999";

                const label = document.createElement("span");
                label.textContent = a.titulo;
                label.style.color = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();
                label.style.fontFamily = "Quicksand";
                label.style.fontSize = "16px";

                item.appendChild(colorBox);
                item.appendChild(label);
                contenedorLeyenda.appendChild(item);
            });
        })

        .catch(err => console.error("Error cargando gráfico semanal:", err));
});

// ------------------------------ Actualizar colores al cambiar de modo ------------------------------

function actualizarColoresGrafico() {
    const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();

    // Gráfico de prioridad
    if (typeof chartPrioridad !== "undefined" && chartPrioridad) {
        chartPrioridad.options.plugins.legend.labels.color = textoColor;
        chartPrioridad.data.datasets.forEach((dataset) => {
            dataset.borderColor = textoColor;
        });
        chartPrioridad.update();
    }

    // Gráfico semanal
    if (typeof chartSemanal !== "undefined" && chartSemanal) {
        chartSemanal.options.scales.x.title.color = textoColor;
        chartSemanal.options.scales.y.title.color = textoColor;
        chartSemanal.options.scales.x.ticks.color = textoColor;
        chartSemanal.options.scales.y.ticks.color = textoColor;
        chartSemanal.update();
    }

    // Leyenda semanal
    const leyendaTextos = document.querySelectorAll('#leyendaSemanal div span:last-child');
    leyendaTextos.forEach(span => {
        span.style.color = textoColor;
    });
}
