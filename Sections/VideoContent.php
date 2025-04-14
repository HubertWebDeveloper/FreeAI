<link rel="stylesheet" href="css/Images.css">

<div class="card">
  <div class="card-header">
    <b>Creation Dream</b>
    <a href="#" class="text-black">
      <i class="bi bi-box-arrow-in-left float-end" style="font-size:18px"></i>
    </a>
  </div>

  <div class="card-body">
    <form method="post" enctype="multipart/form-data" class="row">
      <div class="col-md-6 text-center">
        <div id="drop-area" class="wrapper">
          <span class="text-muted" id="placeholder-text">Click, double-click, or drag videos here</span>
        </div>
        <input id="default-btn" name="videos[]" type="file" accept="video/*" hidden multiple>
        <div id="preview-wrapper" class="preview-container"></div>

        <div id="progress-container" style="display:none">
          <div class="progress">
            <div class="progress-bar" id="progress-bar"></div>
          </div>
          <p id="progress-text">Generating video...</p>
        </div>
      </div>

      <div class="col-md-6 text-center">
        <textarea class="wrapper" name="text" spellcheck="false" required placeholder="Type your paragraphs&#10;&#10;- One paragraph = One clip&#10;- Videos will be rotated unless matched manually"></textarea>

        <div class="input-group mb-3">
          <span class="input-group-text">Playback Speed:</span>
          <select name="speed" class="form-select">
            <option value="0.5">0.5x (Slow)</option>
            <option value="0.75">0.75x</option>
            <option value="1" selected>1x (Normal)</option>
            <option value="1.25">1.25x</option>
            <option value="1.5">1.5x</option>
            <option value="2">2x (Fast)</option>
          </select>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Language:</span>
          <select name="language" class="form-select" required>
            <option value="en">English</option>
            <option value="es">Spanish</option>
            <option value="fr">French</option>
            <option value="de">German</option>
            <option value="hi">Hindi</option>
            <option value="ar">Arabic</option>
            <option value="sw">Swahili</option>
          </select>
        </div>

        <div class="input-group mb-3">
          <span class="input-group-text">Voice:</span>
          <select name="voice" class="form-select" required>
            <option value="female">Female</option>
            <option value="male">Male</option>
          </select>
        </div>

        <button type="submit" class="btn btn-sm" name="convert" id="custom-btn-2">Generate Video</button>
      </div>
    </form>
  </div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['convert'])) {
    $text = trim($_POST['text']);
    $language = $_POST['language'];
    $voice = $_POST['voice'];
    $speed = floatval($_POST['speed']);

    $paragraphs = preg_split("/\n\s*\n+/", $text);
    $lines = array_map('trim', $paragraphs);

    if ($language !== 'en') {
        foreach ($lines as $i => $line) {
            $translated = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=$language&dt=t&q=" . urlencode($line));
            $result = json_decode($translated, true);
            if (isset($result[0][0][0])) {
                $lines[$i] = $result[0][0][0];
            }
        }
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    $videos = $_FILES['videos'];
    $video_paths = [];

    foreach ($videos['tmp_name'] as $index => $tmp_name) {
        if (!is_uploaded_file($tmp_name)) continue;
        $name = preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($videos['name'][$index]));
        $target_path = $upload_dir . time() . "_$index" . '_' . $name;
        if (move_uploaded_file($tmp_name, $target_path)) {
            $video_paths[] = $target_path;
        }
    }

    if (empty($video_paths)) {
        echo "<p style='color:red;'>Error: Please upload at least one video file.</p>";
        exit;
    }

    $video_parts = [];

    foreach ($lines as $i => $line) {
        $video_index = $i % count($video_paths);
        $video_clip = $video_paths[$video_index];

        $encoded = rawurlencode($line);
        $audio_file = $upload_dir . "audio_$i.mp3";

        $tts_url = "https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=$encoded&tl=$language&ttsspeed=$speed";
        file_put_contents($audio_file, file_get_contents($tts_url));

        $cmd = "ffprobe -v error -show_entries format=duration -of csv=p=0 \"$audio_file\"";
        $real_duration = floatval(shell_exec($cmd));
        if ($real_duration <= 0) {
            $word_count = str_word_count($line);
            $real_duration = max(1.5, $word_count / (2 * $speed));
        }

        $video_output = $upload_dir . "part_$i.mp4";
        shell_exec("ffmpeg -y -i \"$video_clip\" -i \"$audio_file\" -c:v libx264 -c:a aac -shortest -t $real_duration \"$video_output\"");
        $video_parts[] = $video_output;
    }

    $list_file = $upload_dir . "videos_to_merge.txt";
    file_put_contents($list_file, implode("\n", array_map(fn($p) => "file '$p'", $video_parts)));

    $final_video = $upload_dir . "final_" . time() . ".mp4";
    shell_exec("ffmpeg -y -f concat -safe 0 -i \"$list_file\" -c copy \"$final_video\"");

    $video_url = 'Sections/uploads/' . basename($final_video);

    echo "<div class='text-center p-3'>";
    echo "<h4>Final Video:</h4>";
    echo "<video width='640' height='350' controls><source src='$video_url' type='video/mp4'></video><br>";
    echo "<a href='$video_url' id='download' class='btn btn-success mt-2' download>Download Video</a>";
    echo "</div>";
}
?>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const wrapper = document.getElementById("drop-area");
  const defaultBtn = document.getElementById("default-btn");
  const previewWrapper = document.getElementById("preview-wrapper");
  const form = document.querySelector("form");
  const progressContainer = document.getElementById("progress-container");
  const progressBar = document.getElementById("progress-bar");
  const progressText = document.getElementById("progress-text");
  const downloadBtn = document.getElementById("download");

  const handleFiles = (files) => {
    [...files].forEach(file => {
      if (!file.type.startsWith("video/")) return;

      const reader = new FileReader();
      reader.onload = function (e) {
        const previewBox = document.createElement("div");
        previewBox.classList.add("preview");

        const video = document.createElement("video");
        video.src = e.target.result;
        video.controls = true;
        video.width = 200;

        const removeBtn = document.createElement("button");
        removeBtn.classList.add("remove-btn");
        removeBtn.innerHTML = "&times;";
        removeBtn.onclick = () => previewWrapper.removeChild(previewBox);

        previewBox.appendChild(video);
        previewBox.appendChild(removeBtn);
        previewWrapper.appendChild(previewBox);
      };
      reader.readAsDataURL(file);
    });
  };

  wrapper.addEventListener("click", () => defaultBtn.click());
  wrapper.addEventListener("dblclick", () => defaultBtn.click());

  defaultBtn.addEventListener("change", function () {
    handleFiles(this.files);
  });

  wrapper.addEventListener("dragover", e => {
    e.preventDefault();
    wrapper.classList.add("dragover");
  });

  wrapper.addEventListener("dragleave", () => {
    wrapper.classList.remove("dragover");
  });

  wrapper.addEventListener("drop", e => {
    e.preventDefault();
    wrapper.classList.remove("dragover");
    handleFiles(e.dataTransfer.files);
  });

  if (form) {
    form.addEventListener("submit", () => {
      progressContainer.style.display = "block";
      progressBar.style.width = "0%";
      progressText.textContent = "Generating video...";

      let progress = 0;
      const interval = setInterval(() => {
        if (progress >= 98) {
          clearInterval(interval);
        } else {
          progress += Math.random() * 5;
          progressBar.style.width = Math.min(progress, 98) + "%";
        }
      }, 500);
    });
  }

  if (downloadBtn) {
    setTimeout(() => {
      downloadBtn.click();
      progressBar.style.width = '100%';
      progressText.textContent = 'Done!';
    }, 2000);
  }
});
document.addEventListener('DOMContentLoaded', function () {
  const autoDownloadBtn = document.getElementById('download');
  if (autoDownloadBtn) {
    // Delay a bit to let the DOM settle
    setTimeout(() => {
      autoDownloadBtn.click();
    }, 1000); // Adjust the delay if needed
  }
});
</script>
