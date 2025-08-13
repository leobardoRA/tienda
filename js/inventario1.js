// Referencias a modales
const modalEditar = document.getElementById("modal-editar");
const modalAgregar = document.getElementById("modal-agregar");

// Abrir y cerrar modales
function abrirAgregar() {
  if (modalAgregar) modalAgregar.style.display = "flex";
}

function cerrarAgregar() {
  if (modalAgregar) modalAgregar.style.display = "none";
}

function cerrarModal() {
  if (modalEditar) modalEditar.style.display = "none";
}

window.onclick = function(event) {
  if (event.target === modalEditar) cerrarModal();
  if (event.target === modalAgregar) cerrarAgregar();
}

// Mensaje flotante
function mostrarMensaje(texto, tipo = "exito") {
  const mensaje = document.getElementById("mensaje-flotante");
  if (!mensaje) return;

  mensaje.textContent = texto;
  mensaje.style.backgroundColor = tipo === "error" ? "#c0392b" : "#082236";
  mensaje.style.display = "block";
  setTimeout(() => mensaje.style.display = "none", 3000);
}

// Contador de productos
function actualizarTotalProductos() {
  fetch("../php/contar_productos.php")
    .then(res => res.text())
    .then(total => {
      const contador = document.getElementById("totalProductos");
      if (contador) contador.textContent = "Total: " + total;
    });
}

// Cargar inventario y activar botones
function cargarInventario() {
  fetch("../php/cargar_inventario.php")
    .then(res => res.text())
    .then(html => {
      const tabla = document.getElementById("tabla-inventario");
      if (tabla) tabla.innerHTML = html;

      actualizarTotalProductos();
      activarBotonesEditar();
      activarBotonesEliminar();
    });
}

// Botón editar
function activarBotonesEditar() {
  const botonesEditar = document.querySelectorAll(".btn-editar");
  botonesEditar.forEach(btn => {
    btn.addEventListener("click", () => {
      document.getElementById("editar_id").value = btn.dataset.id;
      document.getElementById("editar_producto").value = btn.dataset.producto;
      document.getElementById("editar_marca").value = btn.dataset.marca;
      document.getElementById("editar_categoria").value = btn.dataset.categoria;
      document.getElementById("editar_stock").value = btn.dataset.stock;
      document.getElementById("editar_precio").value = btn.dataset.precio;
      document.getElementById("editar_fecha_cad").value = btn.dataset.fecha;
      if (modalEditar) modalEditar.style.display = "flex";
    });
  });
}

// Botón eliminar
function activarBotonesEliminar() {
  const botonesBorrar = document.querySelectorAll(".btn-borrar");

  botonesBorrar.forEach(btn => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.id;
      if (!id) return;

      const confirmar = confirm("¿Eliminar este producto?");
      if (!confirmar) return;

      fetch("../php/eliminar_producto.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "id=" + encodeURIComponent(id)
      })
      .then(res => res.text())
      .then(msg => {
        mostrarMensaje(msg, msg.includes("✅") ? "exito" : "error");
        cargarInventario();
      })
      .catch(() => {
        mostrarMensaje("❌ Error al conectar con el servidor.", "error");
      });
    });
  });
}

// Evento inicial
document.addEventListener("DOMContentLoaded", () => {
  cargarInventario();

  const formAgregar = document.getElementById("form-agregar-producto");
  if (formAgregar) {
    formAgregar.addEventListener("submit", function(e) {
      e.preventDefault();
      const datos = new FormData(this);

      fetch("../php/agregar_producto.php", {
        method: "POST",
        body: datos
      })
      .then(res => res.text())
      .then(msg => {
        mostrarMensaje(msg, msg.includes("✅") ? "exito" : "error");
        cerrarAgregar();
        this.reset();
        cargarInventario();
      })
      .catch(() => {
        mostrarMensaje("❌ Error de conexión con el servidor.", "error");
      });
    });
  }
});