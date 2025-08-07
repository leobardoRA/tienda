const productos = [
  { nombre: "Pepsi 600ml", marca: "Pepsi", categoria: "Bebidas", stock: 30, precio: 14 },
  { nombre: "Pan Bimbo Blanco", marca: "Bimbo", categoria: "Panadería", stock: 20, precio: 28 },
  { nombre: "Leche Lala 1L", marca: "Lala", categoria: "Lácteos", stock: 25, precio: 23 },
  { nombre: "Chetos Flamin'", marca: "Chetos", categoria: "Snacks", stock: 15, precio: 18 },
  { nombre: "Volt 500ml", marca: "Volt", categoria: "Bebidas", stock: 12, precio: 16 },
  { nombre: "Croquetas perro", marca: "Mi perro", categoria: "Mascotas", stock: 8, precio: 54 },
];

function renderProductos() {
  const tbody = document.getElementById("contenido-tabla");
  const totalBtn = document.getElementById("totalProductos");
  tbody.innerHTML = "";
  productos.forEach(p => {
    const fila = document.createElement("tr");
    fila.innerHTML = `
      <td>${p.nombre}</td>
      <td>${p.marca}</td>
      <td>${p.categoria}</td>
      <td>${p.stock}</td>
      <td>$${p.precio.toFixed(2)}</td>
    `;
    tbody.appendChild(fila);
  });
  totalBtn.textContent = `Total: ${productos.length}`;
}

renderProductos();


const modal = document.getElementById("modalEditar");
const cerrar = document.getElementById("cerrarModal");
const formEditar = document.getElementById("formEditar");

// Mostrar el modal al hacer clic en "Editar"
document.querySelectorAll(".btn-editar").forEach(btn => {
  btn.addEventListener("click", () => {
    modal.style.display = "flex";

    const fila = btn.closest("tr");
    const celdas = fila.querySelectorAll("td");

    document.getElementById("nombre").value = celdas[0].textContent;
    document.getElementById("marca").value = celdas[1].textContent;
    document.getElementById("categoria").value = celdas[2].textContent;
    document.getElementById("stock").value = celdas[3].textContent;
    document.getElementById("precio").value = celdas[4].textContent;
  });
});

// Cerrar el modal
cerrar.addEventListener("click", () => {
  modal.style.display = "none";
});

// Guardar los cambios (simulado)
formEditar.addEventListener("submit", (e) => {
  e.preventDefault();
  alert("¡Producto editado correctamente!");
  modal.style.display = "none";
});

/*comprar*/ 
// Referencias al modal
const modal = document.getElementById("modalCompra");
const cerrar = modal.querySelector(".cerrar");
const modalTitulo = document.getElementById("modalTitulo");
const modalImg = document.getElementById("modalImg");
const modalMarca = document.getElementById("modalMarca");
const modalCategoria = document.getElementById("modalCategoria");
const modalPrecio = document.getElementById("modalPrecio");
const modalCantidad = document.getElementById("modalCantidad");
const modalTotal = document.getElementById("modalTotal");

// Al hacer clic en un producto
document.querySelectorAll(".producto").forEach(prod => {
  prod.addEventListener("click", () => {
    const nombre = prod.querySelector("h5").textContent;
    const detalles = prod.querySelector("p").textContent.split("—");
    const marca = detalles[0].trim();
    const categoria = detalles[1].trim();
    const img = prod.querySelector("img").src;

    // Simulación de precio, puedes obtenerlo de la BD
    const precio = 15.00; // Cambia por el precio real

    modalTitulo.textContent = nombre;
    modalImg.src = img;
    modalMarca.textContent = "Marca: " + marca;
    modalCategoria.textContent = "Categoría: " + categoria;
    modalPrecio.textContent = precio.toFixed(2);
    modalCantidad.value = 1;
    modalTotal.textContent = precio.toFixed(2);

    modal.style.display = "block";
  });
});

// Actualizar total cuando cambie la cantidad
modalCantidad.addEventListener("input", () => {
  const precio = parseFloat(modalPrecio.textContent);
  const cantidad = parseInt(modalCantidad.value) || 1;
  modalTotal.textContent = (precio * cantidad).toFixed(2);
});

// Cerrar modal
cerrar.onclick = () => modal.style.display = "none";
window.onclick = e => { if (e.target === modal) modal.style.display = "none"; };
