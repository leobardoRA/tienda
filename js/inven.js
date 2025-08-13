document.addEventListener("DOMContentLoaded", () => {
  cargarGrillaProductos();
  cargarResumenInventario();
  activarFiltroCategorias();
  activarBuscador();
});

// 🧩 Cargar productos visuales
function cargarGrillaProductos() {
  fetch("../php/cargar_grilla.php")
    .then(res => res.text())
    .then(html => {
      const contenedor = document.querySelector(".grid-productos");
      if (contenedor) contenedor.innerHTML = html;
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