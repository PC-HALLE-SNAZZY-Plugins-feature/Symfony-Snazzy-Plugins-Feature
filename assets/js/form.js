document.addEventListener('DOMContentLoaded', (event) => {
    const deleteLabels = document.querySelectorAll('label[for$="_imageFile_delete"]');
    deleteLabels.forEach(label => {
        label.textContent = 'Delete image';
        label.classList.add(
			'custom-delete-label',
			'text-red-600',
			'p-3',
			'cursor-pointer',
			'mb-3'
			
			);
    });
});