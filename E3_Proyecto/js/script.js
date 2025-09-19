// ------------------------------ Modo claro y oscuro ------------------------------

const iconoSol = document.getElementById("iconoSol");
const iconoLuna = document.getElementById("iconoLuna");

// Función para activar el modo claro
function activarClaro()
{
    document.body.classList.remove("dark");
    iconoSol.classList.add("activo");
    iconoLuna.classList.remove("activo");
    localStorage.setItem("theme", "light");
}

// Función para activar el modo oscuro
function activarOscuro()
{
    document.body.classList.add("dark");
    iconoLuna.classList.add("activo");
    iconoSol.classList.remove("activo");
    localStorage.setItem("theme", "dark");
}

// Eventos al tocar los botones
iconoSol.addEventListener("click", activarClaro);
iconoLuna.addEventListener("click", activarOscuro);

// Para que quede guardado el modo en el navegador
document.addEventListener("DOMContentLoaded", () =>
{
    if (localStorage.getItem("theme") === "dark")
    {
        activarOscuro();
    }
    else
    {
        activarClaro();
    }
});

// ------------------------------ Mostrar y ocultar datos de cuenta ------------------------------

// Mostrar/ocultar menú al hacer click
document.addEventListener("DOMContentLoaded", () =>
{
    const cuentaBoton = document.getElementById("cuentaBoton");
    const cuentaDatos = document.getElementById("cuentaDatos");
    const cerrarMenu = document.getElementById("cerrarMenu");

    if (cuentaBoton && cuentaDatos)
    {
        cuentaBoton.addEventListener("click", (e) =>
        {
            e.stopPropagation();
            cuentaDatos.classList.toggle("activo");
        });

        // Para que se cierre al tocar la "x"
        cerrarMenu.addEventListener("click", () =>
        {
            cuentaDatos.classList.remove("activo");
        });

        document.addEventListener("click", (e) =>
        {
            if (!cuentaDatos.contains(e.target) && !cuentaBoton.contains(e.target))
            {
                cuentaDatos.classList.remove("activo");
            }
        });
    }
});