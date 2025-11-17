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

// ------------------------------ Mostrar / Ocultar contraseñas ------------------------------

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.togglePass').forEach(icon => {
        icon.addEventListener('click', () => {
            const input = icon.previousElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
});

// ------------------------------ Validador de contraseña ------------------------------

document.addEventListener("DOMContentLoaded", () => {
    const passwordInput = document.getElementById('contrasenia');
    const lengthReq = document.getElementById('longitud');
    const numberReq = document.getElementById('numero');
    const letterReq = document.getElementById('letra');

    // Evitar errores si no existen los elementos en los otros archivos
    if (!passwordInput || !lengthReq || !numberReq || !letterReq) return;

    // Función para cambiar ícono
    function cambiarIcono(elemento, valido) {
        const icono = elemento.querySelector("i");
        if (valido) {
            icono.classList.remove("fa-circle-xmark");
            icono.classList.add("fa-circle-check");
        } else {
            icono.classList.remove("fa-circle-check");
            icono.classList.add("fa-circle-xmark");
        }
    }

    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;

        // Validación de longitud
        const validLongitud = value.length >= 8;
        lengthReq.classList.toggle('valido', validLongitud);
        cambiarIcono(lengthReq, validLongitud);

        // Validación de número
        const validNumero = /\d/.test(value);
        numberReq.classList.toggle('valido', validNumero);
        cambiarIcono(numberReq, validNumero);

        // Validación de letra
        const validLetra = /[a-zA-Z]/.test(value);
        letterReq.classList.toggle('valido', validLetra);
        cambiarIcono(letterReq, validLetra);
    });
});


// ------------------------------ Mostrar y ocultar datos de cuenta ------------------------------

// Mostrar/ocultar menú al hacer click
document.addEventListener("DOMContentLoaded", () => {
    const cuentaBoton = document.getElementById("cuentaBoton");
    const cuentaDatos = document.getElementById("cuentaDatos");
    const cerrarMenu = document.getElementById("cerrarMenu");
    const fondoBlur = document.getElementById("fondoBlur");

    if (cuentaBoton && cuentaDatos) {
        cuentaBoton.addEventListener("click", (e) => {
            e.stopPropagation();
            const activo = cuentaDatos.classList.toggle("activo");
            fondoBlur.classList.toggle("activo", activo);
        });

        cerrarMenu.addEventListener("click", () => {
            cuentaDatos.classList.remove("activo");
            fondoBlur.classList.remove("activo");
        });

        document.addEventListener("click", (e) => {
            if (!cuentaDatos.contains(e.target) && !cuentaBoton.contains(e.target)) {
                cuentaDatos.classList.remove("activo");
                fondoBlur.classList.remove("activo");
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

// ------------------------------ Generar gráfico informativo ------------------------------

let chartInformativo;

document.addEventListener("DOMContentLoaded", function () {
    fetch("../routers/graficoInfoRouter.php")
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById("graficoInformativo").getContext("2d");
            const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();
            const colorPrincipal = getComputedStyle(document.body).getPropertyValue("--fondoBoton").trim();

            const diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];

            const horasPorDia = {};
            diasSemana.forEach(d => horasPorDia[d] = 0);

            data.forEach(item => {
                horasPorDia[item.dia] = parseFloat(item.horas);
            });

            const dias = diasSemana;
            const horas = dias.map(d => horasPorDia[d]);

            chartInformativo = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: dias,
                    datasets: [
                        {
                            label: "Horas totales por día",
                            data: horas,
                            backgroundColor: colorPrincipal,
                            borderWidth: 0,
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: "Día de la semana",
                                color: textoColor,
                                font: { family: "Quicksand", size: 16 }
                            },
                            ticks: {
                                color: textoColor,
                                font: { family: "Quicksand", size: 14 }
                            },
                            grid: { color: "rgba(150,150,150,0.3)" }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: "Horas totales",
                                color: textoColor,
                                font: { family: "Quicksand", size: 16 }
                            },
                            ticks: {
                                color: textoColor,
                                font: { family: "Quicksand", size: 12 }
                            },
                            grid: { color: "rgba(150,150,150,0.3)" }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: "Distribución semanal",
                            color: textoColor,
                            font: { family: "Quicksand", size: 18 }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const horas = context.parsed.y;
                                    const h = Math.floor(horas);
                                    const m = Math.round((horas - h) * 60);
                                    return `${h}h ${m}m`;
                                }
                            }
                        }
                    }
                }
            });

            // Resumen
            const totalHoras = horas.reduce((a, b) => a + b, 0);
            const maxHoras = Math.max(...horas);
            const minHoras = Math.min(...horas);
            const diaMax = dias[horas.indexOf(maxHoras)];
            const diaMin = dias[horas.indexOf(minHoras)];
            const diasConActividades = data.length;

            const mensajeElem = document.getElementById("mensajeInformativo");
            const tituloElem = document.getElementById("tituloInformativo");

            const promedio = totalHoras / diasConActividades;
            let mensaje = "";

            if (diasConActividades === 1) {
                mensaje = `🕐 <b>Actividad puntual:</b> solo registraste tareas el ${data[0].dia}.`;
            } else if (diasConActividades === 2) {
                mensaje = `📅 <b>Actividad ligera:</b> solo tuviste tareas en dos días (${data[0].dia} y ${data[1].dia}).`;
            } else if (maxHoras - minHoras < 1 && diasConActividades > 2) {
                mensaje = "📊 <b>Semana equilibrada:</b> mantuviste una buena distribución de tiempo.";
            } else if (maxHoras > promedio * 1.5) {
                mensaje = `🔥 <b>Semana intensa:</b> el día más cargado es ${diaMax} (${maxHoras.toFixed(1)}h). Recordá equilibrar tu descanso.`;
            } else if (minHoras === 0) {
                mensaje = `⚖️ <b>Día libre detectado:</b> no hubo actividades el ${diaMin}. Aprovechalo para planificar o descansar.`;
            } else {
                mensaje = `✅ <b>Semana con buena actividad:</b> promedio de ${promedio.toFixed(1)}h diarias.`;
            }

            tituloElem.textContent = "Resumen semanal";
            mensajeElem.innerHTML = mensaje;
        })
        .catch(err => console.error("Error cargando gráfico informativo:", err));
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

    // Gráfico informativo
    if (typeof chartInformativo !== "undefined" && chartInformativo) {
        const textoColor = getComputedStyle(document.body).getPropertyValue("--textoColor").trim();
        const colorPrincipal = getComputedStyle(document.body).getPropertyValue("--fondoBoton").trim();

        chartInformativo.options.scales.x.title.color = textoColor;
        chartInformativo.options.scales.y.title.color = textoColor;
        chartInformativo.options.scales.x.ticks.color = textoColor;
        chartInformativo.options.scales.y.ticks.color = textoColor;
        chartInformativo.options.plugins.title.color = textoColor;

        chartInformativo.data.datasets.forEach(dataset => {
            dataset.backgroundColor = chartInformativo.data.labels.map(() => colorPrincipal);
        });

        chartInformativo.update();
    }

}

// ------------------------------ Animación de partículas de relojes ------------------------------

document.addEventListener("DOMContentLoaded", () => {
    const contenedor = document.getElementById("particulasRelojes");
    if (!contenedor) return;

    const cantidad = 15;
    const iconos = ["fa-clock", "fa-hourglass", "fa-stopwatch", "fa-alarm-clock", "fa-bell"];

    for (let i = 0; i < cantidad; i++) {
        const reloj = document.createElement("i");
        reloj.classList.add("fa-solid", iconos[Math.floor(Math.random() * iconos.length)], "particle");

        // Posición horizontal aleatoria
        reloj.style.left = Math.random() * 100 + "vw";

        // Posición vertical inicial aleatoria (para que no aparezcan todos arriba)
        reloj.style.top = Math.random() * 100 + "vh";

        // Tamaño, opacidad y velocidad
        reloj.style.fontSize = (15 + Math.random() * 20) + "px";
        reloj.style.opacity = (0.3 + Math.random() * 0.7).toFixed(2);
        reloj.style.animationDuration = (10 + Math.random() * 10) + "s";

        // Para que al recargar empiecen al azar
        reloj.style.animationDelay = (-Math.random() * 12) + "s";

        contenedor.appendChild(reloj);
    }
});

document.addEventListener("DOMContentLoaded", () => {
  const introInfo = document.getElementById("introInfo");
  const cerrarIntro = document.getElementById("cerrarIntro");

  const modalIntro = document.getElementById("modalIntro");
  const fondoBlurIntro = document.getElementById("fondoBlurIntro");

  const cerrarModalIntro = document.getElementById("cerrarModalIntro");

  const formIntroCancelar = document.getElementById("formIntroCancelar");
  const formIntroConfirmar = document.getElementById("formIntroConfirmar");

  // Abrir modal al tocar la X de la intro
  cerrarIntro.addEventListener("click", () => {
    modalIntro.classList.add("activo");
    fondoBlurIntro.classList.add("activo");
  });

  // Cerrar modal
  const cerrar = () => {
    modalIntro.classList.remove("activo");
    fondoBlurIntro.classList.remove("activo");
  };

  cerrarModalIntro.addEventListener("click", cerrar);
  formIntroCancelar.addEventListener("submit", cerrar);

  // Confirmar ocultar
  formIntroConfirmar.addEventListener("submit", () => {
    introInfo.style.display = "none";
    cerrar();
  });
});

// ------------------------------ Dashboard ------------------------------
document.addEventListener("DOMContentLoaded", () => {
    fetch("../routers/actividadActualRouter.php")
  .then(res => {
    if (!res.ok) throw new Error("Network response was not ok");
    return res.json();
  })
  .then(data => {
    const cont = document.getElementById("actividadActual");

    // Si viene null o no tiene título, mostramos vista vacía
    if (!data || typeof data !== 'object' || !data.titulo) {
      cont.innerHTML = `
        <div class="actividad-vacia">
          </div>
          <div class="actividad-vacia__texto">
            <strong>No tienes ninguna actividad asignada</strong>
            <p class="actividad-vacia__sub">Aprovechá este tiempo libre para descansar o avanzar tareas pequeñas.</p>
          </div>
        </div>
      `;
      return;
    }

    // Si hay actividad, render normal
    cont.innerHTML = `
      <div class="actividad-activa">
        <div class="actividad-activa__left">
          <span class="actividad-color" style="background:${data.color || '#888'}"></span>
        </div>
        <div class="actividad-activa__body">
          <h4 class="actividad-titulo">${escapeHtml(data.titulo)}</h4>
          <p class="actividad-hora">${escapeHtml(data.hora_inicio)} - ${escapeHtml(data.hora_fin)}</p>
          <p class="actividad-prioridad">${escapeHtml(data.prioridad || '')}</p>
        </div>
      </div>
    `;
  })
  .catch(err => {
    console.error("Error al cargar actividad actual:", err);
    const cont = document.getElementById("actividadActual");
    cont.innerHTML = `<p class="actividad-error">Error cargando la actividad.</p>`;
  });

/* Helper básico para evitar inyección / caracteres raros en HTML interpolado */
function escapeHtml(str) {
  if (!str && str !== 0) return '';
  return String(str)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

});

document.addEventListener("DOMContentLoaded", () => {
    fetch("../routers/proximaActividadRouter.php")
        .then(res => res.json())
        .then(data => {
            const cont = document.getElementById("proximaActividad");

            if (!data) {
                cont.innerHTML = "No hay próximas actividades.";
                return;
            }

            cont.innerHTML = `
                <h4>${data.titulo}</h4>
                <p>${data.hora_inicio} - ${data.hora_fin}</p>
            `;
        });
});

document.addEventListener("DOMContentLoaded", () => {
    fetch("../routers/tareasDiaRouter.php")
        .then(res => res.json())
        .then(tareas => {
            const ul = document.getElementById("tareasDia");
            const barra = document.getElementById("progresoBarra");
            const texto = document.getElementById("progresoTexto");

            ul.innerHTML = "";

            tareas.forEach(t => {
                ul.innerHTML += `
                    <li>
                        <input type="checkbox" class="check-tarea" ${t.completada == 1 ? "checked" : ""}>
                        <span class="titulo-tarea" ${t.completada == 1 ? 'style="text-decoration: line-through; opacity:.6;"' : ''}>
                            ${t.titulo}
                        </span>
                    </li>
                `;
            });

            // Función para actualizar progreso
            function actualizarProgreso() {
                const checks = ul.querySelectorAll(".check-tarea");
                const total = checks.length;
                const marcadas = [...checks].filter(c => c.checked).length;

                const porcentaje = total === 0 ? 0 : Math.round((marcadas / total) * 100);

                barra.style.width = porcentaje + "%";
                texto.textContent = `${porcentaje}% completado`;
            }

            // Aplicar estilos dinámicos y actualizar progreso
            ul.querySelectorAll('.check-tarea').forEach(input => {
                input.addEventListener('change', (e) => {
                    const titulo = e.target.nextElementSibling;
                    if(e.target.checked){
                        titulo.style.textDecoration = 'line-through';
                        titulo.style.opacity = '.6';
                    } else {
                        titulo.style.textDecoration = 'none';
                        titulo.style.opacity = '1';
                    }

                    actualizarProgreso();
                });
            });

            // Calcular progreso inicial al cargar
            actualizarProgreso();
        });
});



document.addEventListener("DOMContentLoaded", () => {
    fetch("../routers/horasLibresRouter.php")
        .then(res => res.json())
        .then(data => {
            const elemLibres = document.getElementById("horasLibres");
            const elemConsejo = document.getElementById("consejoHoras");
            const elemGrafico = document.getElementById("graficoHoras"); // div canvas para mini gráfico

            const horasLibres = (data.libres / 60).toFixed(2);
            const horasOcupadas = (data.ocupados / 60).toFixed(2);
            const totalHoras = parseFloat(horasLibres) + parseFloat(horasOcupadas);
            const porcentajeOcupado = ((horasOcupadas / totalHoras) * 100).toFixed(1);

            elemLibres.innerText = `Horas libres: ${horasLibres} h | Horas ocupadas: ${horasOcupadas} h`;

            // Consejo más detallado según porcentaje
            let consejo = "";
            if (porcentajeOcupado >= 90) {
                consejo = "🔥 Día extremadamente ocupado. Considerá delegar tareas y descansar bien.";
            } else if (porcentajeOcupado >= 75) {
                consejo = "⚠️ Día muy ocupado. Prioriza lo urgente y no te sobrecargues.";
            } else if (porcentajeOcupado >= 50) {
                consejo = "🟠 Día equilibrado pero con carga significativa. Planificá pausas estratégicas.";
            } else if (porcentajeOcupado >= 25) {
                consejo = "🟢 Día relajado. Aprovechá para avanzar en proyectos personales o descansar.";
            } else {
                consejo = "🌿 Día muy tranquilo. Ideal para organizar y planificar la semana.";
            }

            elemConsejo.innerText = consejo;

            // Mini gráfico tipo doughnut para representar porcentaje
            if (elemGrafico) {
                const ctx = elemGrafico.getContext("2d");
                new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["Ocupadas", "Libres"],
                        datasets: [{
                            data: [porcentajeOcupado, 100 - porcentajeOcupado],
                            backgroundColor: ["#ff4d4d", "#4caf50"],
                            borderWidth: 0
                        }]
                    },
                    options: {
                         responsive: false,
                        cutout: "70%",
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (context) => `${context.label}: ${context.parsed}%`
                                }
                            }
                        }
                    }
                });
            }
        });
});


document.addEventListener("DOMContentLoaded", () => {

    fetch("../routers/donutRouter.php")
        .then(res => res.json())
        .then(data => {

            const ctx = document.getElementById("graficoActividades").getContext("2d");

            const totalMinutosDia = 24 * 60;

            // Total minutos actividades
            const minutosActividades = data.reduce((sum, act) => sum + parseInt(act.minutos), 0);

            // Procesar proporciones
            const labels = data.map(x => x.titulo);
            const valores = data.map(x => ((x.minutos / totalMinutosDia) * 100));
            const colores = data.map(x => x.color || "#999");

            // Agregar horas libres
            const porcentajeLibre = ((totalMinutosDia - minutosActividades) / totalMinutosDia * 100);
            labels.push("Horas libres");
            valores.push(porcentajeLibre);
            colores.push("#d3d3d3");

            const textoColor = getComputedStyle(document.body)
                .getPropertyValue("--textoColor")
                .trim();

            // Destruir si existe
            if (window.chartDonut) window.chartDonut.destroy();

            window.chartDonut = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels,
                    datasets: [{
                        data: valores,
                        backgroundColor: colores,
                        borderWidth: 2,
                        borderColor: "rgba(255,255,255,0.35)",
                        hoverOffset: 10
                    }]
                },
                options: {
                    cutout: "60%",
                    layout: { padding: 10 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: "rgba(0,0,0,0.85)",
                            padding: 10,
                            titleFont: { family: "Quicksand", size: 14 },
                            bodyFont: { family: "Quicksand", size: 13 },
                            callbacks: {
                                label: function (context) {
                                    const label = context.label;
                                    const porcentaje = context.raw.toFixed(1);
                                    return `${label}: ${porcentaje}%`;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => console.error("Error Donut:", err));

});




document.addEventListener("DOMContentLoaded", () => {
    fetch("../routers/importantesRouter.php")
        .then(res => res.json())
        .then(lista => {
            const ul = document.getElementById("actividadesImportantes");

            ul.innerHTML = "";

            lista.forEach(act => {
                ul.innerHTML += `
                    <li style="border-left: 4px solid ${act.color}; padding-left: 8px;">
                        <strong>${act.titulo}</strong>
                        (${act.hora_inicio} - ${act.hora_fin})
                    </li>
                `;
            });
        });
});


