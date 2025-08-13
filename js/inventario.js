const botonesEditar = document.querySelectorAll(".btn-editar");
const modal = document.getElementById("modal-editar");

botonesEditar.forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("editar_id").value = btn.dataset.id;
    document.getElementById("editar_producto").value = btn.dataset.producto;
    document.getElementById("editar_marca").value = btn.dataset.marca;
    document.getElementById("editar_categoria").value = btn.dataset.categoria;
    document.getElementById("editar_stock").value = btn.dataset.stock;
    document.getElementById("editar_precio").value = btn.dataset.precio;
    modal.style.display = "block";
  });
});

function cerrarModal() {
  modal.style.display = "none";
}

// Cierra el modal si se da clic afuera
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}


