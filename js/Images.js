const wrapper = document.getElementById("drop-area");
const defaultBtn = document.getElementById("default-btn");

function defaultBtnActive() {
  defaultBtn.click();
}

// Allow clicking or double-clicking to open file selector
wrapper.addEventListener("click", defaultBtnActive);
wrapper.addEventListener("dblclick", defaultBtnActive);

defaultBtn.addEventListener("change", function () {
  handleFiles(this.files);
});

wrapper.addEventListener("dragover", (e) => {
  e.preventDefault();
  wrapper.classList.add("dragover");
});

wrapper.addEventListener("dragleave", () => {
  wrapper.classList.remove("dragover");
});

wrapper.addEventListener("drop", (e) => {
  e.preventDefault();
  wrapper.classList.remove("dragover");
  const files = e.dataTransfer.files;
  handleFiles(files);
});

function handleFiles(files) {
  [...files].forEach(file => {
    if (!file.type.startsWith("image/")) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      const previewBox = document.createElement("div");
      previewBox.classList.add("preview");

      const img = document.createElement("img");
      img.src = e.target.result;

      const removeBtn = document.createElement("button");
      removeBtn.classList.add("remove-btn");
      removeBtn.innerHTML = "&times;";
      removeBtn.onclick = () => wrapper.removeChild(previewBox);

      previewBox.appendChild(img);
      previewBox.appendChild(removeBtn);
      wrapper.appendChild(previewBox);
    };
    reader.readAsDataURL(file);
  });
}
// function handleFiles(files) {
//     const wrapper = document.getElementById("preview-wrapper"); // Ensure there's a container in your HTML
  
//     [...files].forEach(file => {
//       if (!file.type.startsWith("video/")) return;
  
//       const reader = new FileReader();
//       reader.onload = function (e) {
//         const previewBox = document.createElement("div");
//         previewBox.classList.add("preview");
  
//         const video = document.createElement("video");
//         video.src = e.target.result;
//         video.controls = true;
//         video.width = 200; // Set width as needed
  
//         const removeBtn = document.createElement("button");
//         removeBtn.classList.add("remove-btn");
//         removeBtn.innerHTML = "&times;";
//         removeBtn.onclick = () => wrapper.removeChild(previewBox);
  
//         previewBox.appendChild(video);
//         previewBox.appendChild(removeBtn);
//         wrapper.appendChild(previewBox);
//       };
//       reader.readAsDataURL(file);
//     });
//   }
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  const progressContainer = document.getElementById('progress-container');
  const progressBar = document.getElementById('progress-bar');
  const progressText = document.getElementById('progress-text');
  const downloadBtn = document.getElementById('download');
  const directDownload = document.getElementById('direct-download');

  if (form) {
    form.addEventListener('submit', function () {
      progressContainer.style.display = 'block';
      let progress = 0;

      const interval = setInterval(() => {
        if (progress >= 98) {
          clearInterval(interval);
        } else {
          progress += Math.random() * 5;
          progressBar.style.width = Math.min(progress, 98) + '%';
        }
      }, 500);
    });
  }

  if (downloadBtn && directDownload) {
    downloadBtn.addEventListener('click', () => directDownload.click());
    // Auto download
    directDownload.click();
    // Set progress to 100%
    progressBar.style.width = '100%';
    progressText.textContent = 'Done!';
  }
});