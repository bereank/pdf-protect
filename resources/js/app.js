const dropZone = document.querySelector('#drop-zone');
const fileInput = document.querySelector('#pdf-input');
const browseButton = document.querySelector('#browse-button');
const uploadResult = document.querySelector('#upload-result');

if (dropZone && fileInput && browseButton && uploadResult) {
	const showFile = (file) => {
		if (!file) return;
		if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
			uploadResult.textContent = 'Please choose a PDF file.';
			return;
		}
		if (file.size > 25 * 1024 * 1024) {
			uploadResult.textContent = 'That file is larger than 25 MB.';
			return;
		}
		uploadResult.textContent = `${file.name} is ready to protect.`;
	};

	browseButton.addEventListener('click', () => fileInput.click());
	fileInput.addEventListener('change', () => showFile(fileInput.files[0]));
	dropZone.addEventListener('dragover', (event) => {
		event.preventDefault();
		dropZone.classList.add('is-dragging');
	});
	dropZone.addEventListener('dragleave', () => dropZone.classList.remove('is-dragging'));
	dropZone.addEventListener('drop', (event) => {
		event.preventDefault();
		dropZone.classList.remove('is-dragging');
		showFile(event.dataTransfer.files[0]);
	});
}
