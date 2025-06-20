document.addEventListener('DOMContentLoaded', function () {

    // =================================================================
    // SCRIPTS GLOBALES (EJ: MENÚ DE USUARIO)
    // =avadar>
    const userDropdownLink = document.getElementById('userDropdown');
    const userDropdownMenu = document.getElementById('userDropdownMenu');

    if (userDropdownLink && userDropdownMenu) {
        // Función para abrir/cerrar el menú
        const toggleMenu = (event) => {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const isVisible = userDropdownMenu.style.display === 'block';
            if (!isVisible) {
                userDropdownMenu.style.display = 'block';
                userDropdownLink.innerHTML = userDropdownLink.innerHTML.replace('▼', '▲');
            } else {
                userDropdownMenu.style.display = 'none';
                userDropdownLink.innerHTML = userDropdownLink.innerHTML.replace('▲', '▼');
            }
        };

        // Asignar evento al enlace
        userDropdownLink.addEventListener('click', toggleMenu);

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function (event) {
            if (!userDropdownLink.contains(event.target) && !userDropdownMenu.contains(event.target)) {
                if (userDropdownMenu.style.display === 'block') {
                    toggleMenu(); // Llama sin evento para solo cerrar
                }
            }
        });
        
        // Cerrar menú con la tecla 'Escape'
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && userDropdownMenu.style.display === 'block') {
                toggleMenu(); // Llama sin evento para solo cerrar
            }
        });
    }

    // =================================================================
    // SCRIPTS PARA PÁGINA DE PRODUCTO (producto.php)
    // =================================================================
    
    // --- Galería de imágenes ---
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');

    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                // Cambiar imagen principal
                const newImageSrc = this.dataset.imageSrc;
                mainImage.src = newImageSrc;

                // Actualizar la clase 'active'
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // --- Selector de cantidad ---
    const quantityInput = document.getElementById('cantidad');
    const quantityBtnMinus = document.getElementById('quantity-minus');
    const quantityBtnPlus = document.getElementById('quantity-plus');

    if (quantityInput && quantityBtnMinus && quantityBtnPlus) {
        const changeQuantity = (amount) => {
            let currentValue = parseInt(quantityInput.value, 10);
            const min = parseInt(quantityInput.min, 10);
            const max = parseInt(quantityInput.max, 10);
            
            let newValue = currentValue + amount;

            if (newValue < min) newValue = min;
            if (newValue > max) newValue = max;
            
            quantityInput.value = newValue;
        };

        quantityBtnMinus.addEventListener('click', () => changeQuantity(-1));
        quantityBtnPlus.addEventListener('click', () => changeQuantity(1));
    }


    // =================================================================
    // SCRIPTS PARA DASHBOARD DE ADMIN (dashboard_admin.php)
    // =================================================================
    const modalEditarProducto = document.getElementById('modalEditarProducto');
    
    if (modalEditarProducto) {
        // Precargar datos en el modal de edición al abrirse
        modalEditarProducto.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_id').value = button.getAttribute('data-id');
            document.getElementById('edit_nombre').value = button.getAttribute('data-nombre');
            document.getElementById('edit_categoria').value = button.getAttribute('data-categoria');
            document.getElementById('edit_precio').value = button.getAttribute('data-precio');
            document.getElementById('edit_codigo').value = button.getAttribute('data-codigo');
            document.getElementById('edit_stock').value = button.getAttribute('data-stock');
            document.getElementById('edit_descripcion').value = button.getAttribute('data-descripcion');
            document.getElementById('edit_promocion').checked = button.getAttribute('data-promocion') == '1';
            document.getElementById('editarMensajeAjax').innerHTML = '';
        });

        // Mostrar modal de confirmación antes de guardar
        const btnConfirmarEdicion = document.getElementById('btnConfirmarEdicion');
        const modalConfirmarGuardar = new bootstrap.Modal(document.getElementById('modalConfirmarGuardar'));
        if (btnConfirmarEdicion) {
            btnConfirmarEdicion.addEventListener('click', function() {
                modalConfirmarGuardar.show();
            });
        }

        // Enviar formulario por AJAX al confirmar
        const btnGuardarAjax = document.getElementById('btnGuardarAjax');
        if(btnGuardarAjax) {
            btnGuardarAjax.addEventListener('click', function() {
                const form = document.getElementById('formEditarProducto');
                const formData = new FormData(form);
                
                // Usamos una URL explícita para el fetch
                fetch('dashboard_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    // Actualizar dinámicamente la tabla y mostrar mensaje
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const nuevoMensaje = doc.querySelector('.card-body #mensaje_editar_ajax'); // ID único para el mensaje
                    const nuevaTablaBody = doc.querySelector('#tablaProductos tbody');
                    
                    if (nuevoMensaje) {
                        document.getElementById('editarMensajeAjax').innerHTML = nuevoMensaje.innerHTML;
                    }
                    if (nuevaTablaBody) {
                        document.querySelector('#tablaProductos tbody').innerHTML = nuevaTablaBody.innerHTML;
                    }
                    
                    modalConfirmarGuardar.hide();
                    
                    // Si el mensaje es de éxito, cerramos el modal de edición también tras un momento
                    if(nuevoMensaje && nuevoMensaje.querySelector('.alert-success')) {
                       setTimeout(() => {
                           const modalInstance = bootstrap.Modal.getInstance(modalEditarProducto);
                           modalInstance.hide();
                       }, 1500);
                    }
                })
                .catch(err => {
                    document.getElementById('editarMensajeAjax').innerHTML = '<div class="alert alert-danger mt-3">Error de red al intentar guardar los cambios.</div>';
                    modalConfirmarGuardar.hide();
                    console.error('Error en fetch:', err);
                });
            });
        }
    }
}); 