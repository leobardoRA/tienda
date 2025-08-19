document.addEventListener("DOMContentLoaded", () => {
  cargarGrillaProductos(); // Activará botones después de insertar
  cargarResumenInventario();
  activarFiltroCategorias();
  activarBuscador();
});

// 🧩 Cargar productos visuales y activar botones después
function cargarGrillaProductos() {
  fetch("../php/cargar_grilla.php")
    .then(res => res.text())
    .then(html => {
      const contenedor = document.querySelector(".grid-productos");
      if (contenedor) {
        contenedor.innerHTML = html;
        activarBotonesAgregar(); // ✅ Activar después de insertar
      }
    });
}

// 🧮 Cargar resumen de inventario
function cargarResumenInventario() {
  fetch("../php/resumen_inventario.php")
    .then(res => res.json())
    .then(data => {
      const total = document.getElementById("totalProductosStat");
      const valor = document.getElementById("valorInventarioStat");
      if (total) total.textContent = data.total;
      if (valor) valor.textContent = "$" + data.valor;
    });
}

// 🔍 Buscador por texto
function activarBuscador() {
  const input = document.getElementById("busquedaInput");
  if (!input) return;

  input.addEventListener("input", () => {
    const filtro = input.value.toLowerCase();
    const productos = document.querySelectorAll(".producto");

    productos.forEach(p => {
      const nombre = p.dataset.nombre?.toLowerCase() || "";
      const categoria = p.dataset.categoria?.toLowerCase() || "";
      const marca = p.querySelector("p")?.textContent.toLowerCase() || "";

      const coincide = nombre.includes(filtro) || categoria.includes(filtro) || marca.includes(filtro);
      p.style.display = coincide ? "block" : "none";
    });
  });
}

// 🧾 Activar botón "Agregar" con delegación
function activarBotonesAgregar() {
  document.querySelectorAll(".btn-agregar").forEach(btn => {
    btn.addEventListener("click", (e) => {
      const card = e.target.closest(".producto");
      const nombre = card.dataset.nombre || "Sin nombre";
      const precio = parseFloat(card.dataset.precio || "0");

      agregarAlCarrito(nombre, precio);
    });
  });
}

// 🧩 Filtro por categoría
function activarFiltroCategorias() {
  const botones = document.querySelectorAll(".categoria");
  if (!botones.length) return;

  botones.forEach(btn => {
    btn.addEventListener("click", () => {
      const categoriaSeleccionada = btn.dataset.categoria;
      const productos = document.querySelectorAll(".producto");

      productos.forEach(p => {
        const categoriaProducto = p.dataset.categoria;
        const mostrar = categoriaSeleccionada === "*" || categoriaProducto === categoriaSeleccionada;
        p.style.display = mostrar ? "block" : "none";
      });

      // Polish visual: marcar activo
      botones.forEach(b => b.classList.remove("activa"));
      btn.classList.add("activa");
    });
  });
}

// 🧾 Función para agregar al carrito
function agregarAlCarrito(nombre, precio) {
  if (!nombre || isNaN(precio)) {
    console.warn("Producto inválido:", nombre, precio);
    return;
  }

  const lista = document.getElementById("lista-carrito");
  const item = document.createElement("li");
  item.textContent = `${nombre} - $${precio.toFixed(2)}`;
  lista.appendChild(item);

  const totalEl = document.getElementById("total-carrito");
  let totalActual = parseFloat(totalEl.textContent.replace("Total: $", "")) || 0;
  totalActual += precio;
  totalEl.textContent = `Total: $${totalActual.toFixed(2)}`;

  document.getElementById("btn-finalizar").disabled = false;

  const badge = document.getElementById("badgeCarrito");
  badge.textContent = parseInt(badge.textContent) + 1;
}