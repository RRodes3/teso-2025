

    //Este es otro codigo relacionado que no debe ser modificado:
document.addEventListener("DOMContentLoaded", function () {
    const inputFile = document.querySelector('input[name="fotos_muestra[]"]');
    const previewContainer = document.querySelector('.preview-container');
    const imageCounter = document.getElementById('imageCounter');
    const form = document.querySelector('#fotosalmacen');
    // Almacenamiento de archivos
    const filesMap = new Map();
    let fileCounter = 0;
    // Función para actualizar el contador
    function updateImageCounter() {
        const count = filesMap.size;
        imageCounter.textContent = `${count} ${count === 1 ? 'imagen' : 'imágenes'}`;
    }
    // Manejar selección de archivos
    inputFile.addEventListener('change', function(e) {
        const files = Array.from(this.files || []);
        
        files.forEach(file => {
            if (!file.type.startsWith('image/')) {
                alert('Por favor, seleccione solo imágenes');
                return;
            }
            const fileId = `file-${fileCounter++}`;
            filesMap.set(fileId, file);

            const reader = new FileReader();
            reader.onload = function(event) {
                const previewCard = createPreviewCard(event.target.result, file.name, fileId);
                previewContainer.appendChild(previewCard);
                updateImageCounter(); // Actualizar contador
            };
            reader.readAsDataURL(file);
        });

        this.value = '';
    });

    // Crear tarjeta de vista previa
    function createPreviewCard(imgSrc, fileName, fileId) {
        const card = document.createElement('div');
        card.className = 'preview-item';
        card.dataset.fileId = fileId;
        card.innerHTML = `
            <div class="box box-widget">
                <div class="box-body">
                    <img src="${imgSrc}" alt="${fileName}" style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="preview-info" style="text-align: center; margin-top: 5px;">
                        <small style="display: block; word-break: break-all;">${fileName}</small>
                        <button type="button" class="btn btn-danger btn-xs delete-btn">
                            <i class="fa fa-trash"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Agregar evento para eliminar
        const deleteBtn = card.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', () => {
            filesMap.delete(fileId);
            card.remove();
            updateImageCounter(); // Actualizar contador al eliminar
        });
        return card;
    }

  form.addEventListener('submit', function (e) {
    // Validación previa
    if (filesMap.size === 0) {
        e.preventDefault();
        alert('Por favor, seleccione al menos una imagen');
        return;
    }

    // Reconstruir FileList en el input file para envío nativo
    try {
        const dt = new DataTransfer(); // para navegadores modernos
        for (const [, file] of filesMap) {
            dt.items.add(file);
        }
        inputFile.files = dt.files;
        // No llamar a e.preventDefault() -> el formulario se enviará normalmente por POST
        // (el form ya tiene method="post" enctype="multipart/form-data" action="recibeFile.php")
    } catch (err) {
        // Fallback: si no se puede asignar files al input, prevenir envío y avisar
        e.preventDefault();
        console.error('No se pudo preparar files para envío nativo:', err);
        alert('Error al preparar archivos para envío. Intente recargar la página.');
    }
});


});
