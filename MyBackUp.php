
<div class="row">
  <!-- === image upload section === -->
  <div class="col-md-6 p-4">
    <b class="text-uppercase">Actors Section 
      <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="bi bi-cloud-arrow-up-fill"></i>
      </button>
    </b>

    <div class="slide-buttons text-center">
      <button id="prevBtn" style="border:none;background:none">
        <i class="bi bi-arrow-left-circle-fill" style="font-size:23px"></i>
      </button>
      <button id="nextBtn" style="border:none;background:none">
        <i class="bi bi-arrow-right-circle-fill" style="font-size:23px"></i>
      </button>
    </div>

    <div class="image-section mb-2 mt-2">
      <img id="mainImage" src="picture.png" alt="Img"
        style="border:2px solid black;object-fit:fill;width:100%;height:375px;border-radius:20px;">
    </div>

    <div id="imageContainer" style="display: none;">
      <div class="images-display d-flex" id="imagesDisplay" style="gap:5px;overflow-x:hidden;">
        <!-- Thumbnails injected here -->
      </div>
    </div>
  </div>

  <!-- === video upload section === -->
  <div class="col-md-6 p-4">
    <b class="text-uppercase">Background Section 
      <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#videoUpload">
        <i class="bi bi-cloud-arrow-up-fill"></i>
      </button>
    </b>

    <div class="slide-buttons2 text-center d-none">
      <button id="prevBtn2" style="border:none;background:none">
        <i class="bi bi-arrow-left-circle-fill" style="font-size:23px"></i>
      </button>
      <button id="nextBtn2" style="border:none;background:none">
        <i class="bi bi-arrow-right-circle-fill" style="font-size:23px"></i>
      </button>
    </div>

    <div class="video-section mb-2 mt-2">
      <video id="mainVideo" controls style="border:2px solid black;object-fit:contain;width:100%;height:375px;border-radius:20px;"></video>
    </div>

    <div id="VideoContainer" style="display: none;">
      <div class="video-display d-flex" id="VideoDisplay" style="gap:5px;overflow-x:hidden;">
        <!-- Thumbnails injected here -->
      </div>
    </div>
  </div>
</div>

<!-- === Generate Button === -->
<div class="text-center mt-4">
  <button id="generateBtn" class="btn btn-info btn-sm">Generate Video</button>
</div>
    </div>
    
</div>
<!-- ============================= upload images model ============================= -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Actor Upload</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="wrapper">
          <div class="alert alert-danger" role="alert" id="messageBox"style="display:none"></div>
          <form action="#">
          <input class="file-input" type="file" name="file" accept="image/*" hidden>
          <div class="custom-upload text-center" id="customUploadButton">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Browse Images to Upload</p>
          </div>
          </form>
          <section class="progress-area"></section>
          <section class="uploaded-area"></section>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" id="imageUpload" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>
<!-- ============================= upload video&images model ============================= -->
<div class="modal fade" id="videoUpload" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Background Upload</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="wrapper">
          <div class="alert alert-danger" role="alert" id="messageBox"style="display:none"></div>
          <form action="#">
          <input class="file-input2" type="file" name="file" accept="image/*,video/*" hidden>
          <div class="custom-upload text-center" id="customVideoUploadButton">
            <i class="fas fa-cloud-upload-alt"></i>
            <p>Browse Images or Videos to Upload</p>
          </div>
          </form>
          <section class="progress-area2"></section>
          <section class="uploaded-area2"></section>
        </div>
      </div>
      <div class="modal-footer">
      <button type="button" id="VideoUploads" class="btn btn-secondary" data-bs-dismiss="modal">Continue</button>
      </div>
    </div>
  </div>
</div>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
  <script>
  document.getElementById('imageUpload').addEventListener('click', function() {
    document.getElementById('slideButtons').classList.remove('d-none');
    document.getElementById('imagesDisplay').classList.remove('d-none');
  });
  // ------------- allowing user to upload image ------------------
  const form = document.querySelector("form"),
  fileInput = document.querySelector(".file-input"),
  fileInput2 = document.querySelector(".file-input2"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area"),
  progressArea2 = document.querySelector(".progress-area2"),
  uploadedArea2 = document.querySelector(".uploaded-area2"),
  customUploadButton = document.getElementById("customUploadButton"),
  customVideoUploadButton = document.getElementById("customVideoUploadButton"),
  messageBox = document.getElementById("messageBox");
  // Trigger hidden input on custom upload click
  customUploadButton.addEventListener("click", () => {
    fileInput.click();
  });
  customVideoUploadButton.addEventListener("click", () => {
    fileInput2.click();
  });

  fileInput.onchange = ({ target }) => {
    let file = target.files[0];
    messageBox.textContent = "";
    messageBox.style.display = "none";

    // Validate file is an image
    if (file && file.type.startsWith("image/")) {
      let fileName = file.name;
      if (fileName.length >= 12) {
        let splitName = fileName.split('.');
        fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
      }
      uploadFile(fileName, file);
    } else {
      messageBox.style.display = "block";
      messageBox.textContent = "Please upload a Only image file.";
      fileInput.value = ""; // Reset input if invalid
    }
  };
  fileInput2.onchange = ({ target }) => {
    const file = target.files[0];
    messageBox.textContent = "";
    messageBox.style.display = "none";

    // Validate file is an image or video
    if (file && (file.type.startsWith("image/") || file.type.startsWith("video/"))) {
      let fileName = file.name;
      if (fileName.length >= 12) {
        const splitName = fileName.split('.');
        const namePart = splitName.slice(0, -1).join('.'); // Handles filenames with multiple dots
        const ext = splitName[splitName.length - 1];
        fileName = namePart.substring(0, 13) + "... ." + ext;
      }
      uploadFile2(fileName, file); // Assuming uploadFile is your defined function
    } else {
      messageBox.style.display = "block";
      messageBox.textContent = "Please upload only image or video files.";
      fileInput2.value = ""; // Reset input if invalid
    }
  };

  function uploadFile(name, file) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "upload.php");

    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1000);
      let fileSize =
        fileTotal < 1024
          ? fileTotal + " KB"
          : (loaded / (1024 * 1024)).toFixed(2) + " MB";

      let progressHTML = `<li class="row">
                            <div class="content">
                              <div class="details">
                                <span class="name">${name} • Uploading</span>
                                <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                            </div>
                          </li>`;
      uploadedArea.classList.add("onprogress");
      progressArea.innerHTML = progressHTML;

      if (loaded == total) {
        progressArea.innerHTML = "";
        let uploadedHTML = `<li class="row">
                              <div class="content upload">
                                <div class="details">
                                  <span class="name">${name} • Uploaded</span>
                                  <span class="size">${fileSize}</span>
                                  <i class="fas fa-check"></i>
                                </div>
                              </div>
                            </li>`;
        uploadedArea.classList.remove("onprogress");
        uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
      }
    });

    // Prepare form data
    let data = new FormData();
    data.append("file", file);
    xhr.send(data);
  }
  function uploadFile2(name, file) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "upload.php");

    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1000);
      let fileSize =
        fileTotal < 1024
          ? fileTotal + " KB"
          : (loaded / (1024 * 1024)).toFixed(2) + " MB";

      let progressHTML = `<li class="row">
                            <div class="content">
                              <div class="details">
                                <span class="name">${name} • Uploading</span>
                                <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                            </div>
                          </li>`;
      uploadedArea2.classList.add("onprogress");
      progressArea2.innerHTML = progressHTML;

      if (loaded == total) {
        progressArea2.innerHTML = "";
        let uploadedHTML = `<li class="row">
                              <div class="content upload">
                                <div class="details">
                                  <span class="name">${name} • Uploaded</span>
                                  <span class="size">${fileSize}</span>
                                  <i class="fas fa-check"></i>
                                </div>
                              </div>
                            </li>`;
        uploadedArea2.classList.remove("onprogress");
        uploadedArea2.insertAdjacentHTML("afterbegin", uploadedHTML);
      }
    });

    // Prepare form data
    let data = new FormData();
    data.append("file", file);
    xhr.send(data);
  }
  let imageList = [];
  let VideoList = [];
  let currentIndex = 0;

  // Show image by index
  function displayImage(index) {
    const mainImg = document.getElementById('mainImage');
    mainImg.src = `files/${imageList[index]}`;
  }
  // function displayVideo(index) {
  //   const mainVd = document.getElementById('mainVideo');
  //   mainVd.src = `files/${VideoList[index]}`;
  // }

  // Continue Button Click
  document.getElementById('imageUpload').addEventListener('click', async function () {
    try {
      const response = await fetch('list-files.php');
      const fileList = await response.json();

      const imagesDisplay = document.getElementById('imagesDisplay');
      const imageContainer = document.getElementById('imageContainer');
      imagesDisplay.innerHTML = '';
      imageList = fileList;

      if (fileList.length > 0) {
        imageContainer.style.display = 'block';
        currentIndex = 0;
        displayImage(currentIndex);

        fileList.forEach((fileName, index) => {
          const img = document.createElement('img');
          img.src = `files/${fileName}`;
          img.alt = fileName;
          img.style = 'width:130px;height:130px;border-radius:5px;cursor:pointer;';
          
          img.addEventListener('click', () => {
            currentIndex = index;
            displayImage(currentIndex);
          });

          imagesDisplay.appendChild(img);
          document.querySelector('.slide-buttons')?.classList.remove('d-none'); // optional hide
        });
      } else {
        imageContainer.style.display = 'none';
      }
    } catch (error) {
      console.error('Error loading uploaded images:', error);
    }
  });
  document.getElementById('VideoUploads').addEventListener('click', async function () {
    try {
      const response = await fetch('listVideo-files.php');
      const fileList = await response.json();

      VideoList = fileList;
      VideoDisplay.innerHTML = '';

      if (fileList.length > 0) {
        VideoContainer.style.display = 'block';
        currentIndex = 0;
        displayVideo(currentIndex);

        fileList.forEach((fileName, index) => {
          const vid = document.createElement('video');
          vid.src = `files/${fileName}`;
          vid.setAttribute('title', fileName);
          vid.setAttribute('width', '130');
          vid.setAttribute('height', '130');
          vid.setAttribute('preload', 'metadata');
          vid.muted = true;
          vid.style.cssText = 'border-radius:5px; cursor:pointer; object-fit:cover;';

          vid.addEventListener('mouseenter', () => vid.setAttribute('controls', ''));
          vid.addEventListener('mouseleave', () => vid.removeAttribute('controls'));

          vid.addEventListener('click', () => {
            mainVideo.src = `files/${fileName}`;
            mainVideo.play();
            currentIndex = index;
          });

          VideoDisplay.appendChild(vid);
        });

        document.querySelector('.slide-buttons2')?.classList.remove('d-none');
      } else {
        VideoContainer.style.display = 'none';
      }
    } catch (error) {
      console.error('Error loading uploaded videos:', error);
    }
  });

  function displayVideo(index) {
    if (VideoList.length > 0 && VideoList[index]) {
      mainVideo.src = `files/${VideoList[index]}`;
      mainVideo.play();
    }
  }
  // Slide Left
  document.getElementById('prevBtn').addEventListener('click', () => {
    if (imageList.length > 0) {
      currentIndex = (currentIndex - 1 + imageList.length) % imageList.length;
      Image(currentIndex);
      displayImage(currentIndex);
    }
  });
  document.getElementById('prevBtn2').addEventListener('click', () => {
    if (VideoList.length > 0) {
      currentIndex = (currentIndex - 1 + VideoList.length) % VideoList.length;
      Image(currentIndex);
      displayVideo(currentIndex);
    }
  });
  // Slide Right
  document.getElementById('nextBtn').addEventListener('click', () => {
    if (imageList.length > 0) {
      currentIndex = (currentIndex + 1) % imageList.length;
      displayImage(currentIndex);
    }
  });
  document.getElementById('nextBtn2').addEventListener('click', () => {
    if (VideoList.length > 0) {
      currentIndex = (currentIndex + 1) % VideoList.length;
      displayVideo(currentIndex);
    }
  });

  //delete current image when refresh the page
  window.addEventListener('DOMContentLoaded', async () => {
    // 1. Call backend to delete all uploaded images from /files
    try {
      await fetch('delete-files.php', { method: 'POST' }); // use POST to avoid accidental triggering
    } catch (err) {
      console.error("Could not clear files:", err);
    }

    // 2. Reset UI elements
    document.getElementById('imageContainer').style.display = 'none';
    document.getElementById('VideoContainer').style.display = 'none';
    document.querySelector('.slide-buttons')?.classList.add('d-none'); // optional hide
    const mainImg = document.getElementById('mainImage');
    const mainVd = document.getElementById('mainVideo');
    if (mainImg) {
      mainImg.src = 'picture.png';
    }
    if (mainVd) {
      mainVd.src = 'video.mp4';
    }
  });
   let imageFiles = [];
 let videoFiles = [];

// document.getElementById('generateBtn').addEventListener('click', async () => {
//   if (!imageFiles.length || !videoFiles.length) {
//     alert("Please upload both images and videos.");
//     return;
//   }

//   const formData = new FormData();
//   imageFiles.forEach((file, i) => formData.append('images[]', file, `image${i}.png`));
//   videoFiles.forEach((file, i) => formData.append('videos[]', file, `video${i}.mp4`));

//   const response = await fetch('/generate-video', {
//     method: 'POST',
//     body: formData
//   });

//   const blob = await response.blob();
//   const url = URL.createObjectURL(blob);
//   const a = document.createElement('a');
//   a.href = url;
//   a.download = 'final_video.mp4';
//   a.click();
// });
// document.getElementById('generateBtn').addEventListener('click', async () => {
//   const formData = new FormData();

//   // === Collect images from thumbnails ===
//   const imageElements = document.querySelectorAll('#imagesDisplay img');
//   for (const img of imageElements) {
//     try {
//       const res = await fetch(img.src);
//       const blob = await res.blob();
//       const file = new File([blob], 'image.png', { type: blob.type });
//       formData.append('images[]', file);
//     } catch (e) {
//       console.error("Failed to fetch image", img.src);
//     }
//   }

//   // === Collect videos from thumbnails ===
//   const videoElements = document.querySelectorAll('#VideoDisplay video');
//   for (const vid of videoElements) {
//     try {
//       const res = await fetch(vid.src);
//       const blob = await res.blob();
//       const file = new File([blob], 'video.mp4', { type: blob.type });
//       formData.append('videos[]', file);
//     } catch (e) {
//       console.error("Failed to fetch video", vid.src);
//     }
//   }

//   // === Send to PHP backend ===
//   try {
//     const response = await fetch('generate-video.php', {
//       method: 'POST',
//       body: formData
//     });

//     if (!response.ok) throw new Error("Server error during video generation");

//     const blob = await response.blob();
//     const url = URL.createObjectURL(blob);
//     const a = document.createElement('a');
//     a.href = url;
//     a.download = 'final_video.mp4';
//     a.click();
//   } catch (err) {
//     console.error(err);
//     alert("Failed to generate video. See console for details.");
//   }
// });
document.getElementById('generateBtn').addEventListener('click', async () => {
  const formData = new FormData();

  // === Collect images from thumbnails ===
  const imageElements = document.querySelectorAll('#imagesDisplay img');
  for (const img of imageElements) {
    const res = await fetch(img.src);
    const blob = await res.blob();
    const file = new File([blob], 'image.png', { type: blob.type });
    formData.append('images[]', file);
  }

  // === Collect videos from thumbnails ===
  const videoElements = document.querySelectorAll('#VideoDisplay video');
  for (const vid of videoElements) {
    const res = await fetch(vid.src);
    const blob = await res.blob();
    const file = new File([blob], 'video.mp4', { type: blob.type });
    formData.append('videos[]', file);
  }

  try {
    const response = await fetch('generate-video.php', {
      method: 'POST',
      body: formData
    });

    if (!response.ok) throw new Error('Failed to generate video');

    const blob = await response.blob();
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'final_video.mp4';
    a.click();
  } catch (error) {
    console.error(error);
    alert('Error generating video. See console.');
  }
});
