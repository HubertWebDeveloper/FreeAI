<link rel="stylesheet" href="css/Images.css">

<div class="card">
  <div class="card-header">
    <b>Creation Dream</b>
    <a href="#" class="text-black">
      <i class="bi bi-box-arrow-in-left float-end" style="font-size:18px"></i>
    </a>
  </div>

  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data" class="row">
      <div class="col-md-6 text-center">
        <div class="input-group mb-3">
          <span class="input-group-text">Video Size:</span>
          <select name="video_size" class="form-select" required>
            <option value="1280:720">YouTube (16:9)</option>
            <option value="1080:1920">TikTok (9:16)</option>
            <option value="1080:1080">Instagram (1:1)</option>
            <option value="640:480">Normal (4:3)</option>
          </select>
        </div>
        <div class="input-group mb-3">
          <span class="input-group-text">Transition:</span>
          <select name="transition" class="form-select" required>
            <option value="fade" selected>Fade</option>
            <option value="wipeleft">Wipe Left</option>
            <option value="wiperight">Wipe Right</option>
            <option value="slideup">Slide Up</option>
            <option value="slidedown">Slide Down</option>
            <!-- You can add more transitions -->
          </select>
        </div>
        <!-- Drop area and image previews -->
        <div class="wrapper" id="drop-area">
          <span class="text-muted" id="placeholder-text">Click, double-click, or drag images here</span>
        </div>

        <!-- Hidden input for files -->
        <input id="default-btn" name="images[]" type="file" accept="image/*" hidden multiple>

        <!-- Progress Bar -->
        <div id="progress-container">
          <div class="progress">
            <div class="progress-bar" id="progress-bar"></div>
          </div>
          <p id="progress-text">Generating video...</p>
        </div>
      </div>

      
      <div class="col-md-6 text-center">
      <textarea class="wrapper" name="text" spellcheck="false" required placeholder="Type your content according to the images uploaded&#10;&#10;- One paragraph = One image&#10;- Separate paragraphs with a blank line"></textarea>

        <div class="input-group mb-3">
          <span class="input-group-text">Playback Speed:</span>
          <select name="speed" id="speed" class="form-select">
            <option value="0.5">0.5x - Baby Reader (Very Slow)</option>
            <option value="0.75">0.75x - Beginner Reader (Slow)</option>
            <option value="0.9">0.9x - Movie Reader (Cinematic Pace)</option>
            <option value="1" selected>1x - Storyteller (Normal)</option>
            <option value="1.2">1.2x - Emotion Reader (Excited & Intense)</option>
            <option value="1.25">1.25x - Expressive Reader (Slightly Fast)</option>
            <option value="1.5">1.5x - Enthusiastic Narrator (Fast)</option>
            <option value="2">2x - Speed Reader (Very Fast)</option>
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
                <!-- Add more languages as needed -->
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

  <?php
  $show_footer = false;
  if (isset($_POST['convert'])) {
    $text = trim($_POST['text']);
    $language = $_POST['language'];
    $voice = $_POST['voice'];
    $speed = floatval($_POST['speed']);
    $video_size = $_POST['video_size'];
    $reader_style = $_POST['reader_style'] ?? 'normal';
    $transition = $_POST['transition'];

    $paragraphs = preg_split("/\n\s*\n+/", $text); // split on blank lines = paragraphs
    $lines = array_map('trim', $paragraphs);

    // Adjust speed based on reader style
    switch ($reader_style) {
      case 'baby':
          $speed *= 0.6; // baby = slower
          break;
      case 'storyteller':
          $speed *= 0.85; // storyteller = slower but more dynamic
          break;
      case 'normal':
      default:
          // keep as-is
          break;
    }

    // Translate text if language is not English
    if ($language !== 'en') {
        foreach ($lines as $i => $line) {
            //$translated = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=$language&dt=t&q=" . urlencode($line));
            $tts_url = "https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=$encoded&tl=$language&ttsspeed=$speed";

            $result = json_decode($translated, true);
            if (isset($result[0][0][0])) {
                $lines[$i] = $result[0][0][0];
            }
        }
    }

    $upload_dir = __DIR__ . '/uploads/';
    if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

    $images = $_FILES['images'];
    $image_paths = [];

    foreach ($images['tmp_name'] as $index => $tmp_name) {
        $name = preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($images['name'][$index]));
        $target_path = $upload_dir . time() . "_$index" . '_' . $name;
        move_uploaded_file($tmp_name, $target_path);
        $image_paths[] = $target_path;
    }

    if (count($image_paths) !== count($lines)) {
        echo "<p style='color:red;'>Error: The number of images must match the number of text paragraphs.</p>";
        exit;
    }

    $video_parts = [];

    foreach ($lines as $i => $line) {
        $img = $image_paths[$i];
        $encoded = rawurlencode($line);
        $audio_file = $upload_dir . "audio_$i.mp3";

        // TTS download (language-aware)
        $tts_url = "https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=$encoded&tl=$language&ttsspeed=$speed";
        file_put_contents($audio_file, file_get_contents($tts_url));

        // Estimate duration
        $word_count = str_word_count($line);
        $estimated_duration = max(1.5, $word_count / (2 * $speed));

        // Actual duration
        $cmd = "ffprobe -v error -show_entries format=duration -of csv=p=0 \"$audio_file\"";
        $real_duration = floatval(shell_exec($cmd));
        $duration = $real_duration > 0 ? $real_duration : $estimated_duration;

        // Generate video
        $video_part = $upload_dir . "part_$i.mp4";
        $cmd = "ffmpeg -y -loop 1 -i \"$img\" -i \"$audio_file\" -c:v libx264 -t $duration -pix_fmt yuv420p -vf \"scale={$video_size}:force_original_aspect_ratio=decrease,pad={$video_size}:(ow-iw)/2:(oh-ih)/2:color=black\" \"$video_part\"";
        shell_exec($cmd);
        $video_parts[] = $video_part;
    }

    // Merge videos
    $filter_complex = '';
$inputs = '';
$duration = 1; // 1 second transition
$transition = $_POST['transition']; // like 'fade', 'wipeleft', etc.

foreach ($video_parts as $index => $part) {
    $inputs .= "-i \"$part\" ";
}

$filter_index = 0;
$video_label = '';
$audio_label = '';

for ($i = 0; $i < count($video_parts) - 1; $i++) {
    $v_in1 = $i === 0 ? "{$i}:v" : "v" . ($filter_index - 1);
    $a_in1 = $i === 0 ? "{$i}:a" : "a" . ($filter_index - 1);

    $v_in2 = ($i + 1) . ":v";
    $a_in2 = ($i + 1) . ":a";

    $offset = ($i + 1) * $duration;

    $filter_complex .= "[{$v_in1}][{$v_in2}]xfade=transition={$transition}:duration={$duration}:offset={$offset}[v{$filter_index}];";

    //$filter_complex .= "[{$a_in1}][{$a_in2}]acrossfade=d={$duration}[a{$filter_index}];";

    $video_label = "v$filter_index";
    $audio_label = "a$filter_index";
    $filter_index++;
}

$final_output = $upload_dir . "final_" . time() . ".mp4";
$cmd = "ffmpeg -y $inputs -filter_complex \"$filter_complex\" -map \"[$video_label]\" -map \"[$audio_label]\" \"$final_output\"";
shell_exec($cmd);

    // $final_video = $upload_dir . "final_" . time() . ".mp4";
    // shell_exec("ffmpeg -y -f concat -safe 0 -i \"$list_file\" -c copy \"$final_video\"");

    $timestamp = time();
    $final_raw_video = $upload_dir . "final_raw_$timestamp.mp4";
    $final_video = $upload_dir . "final_$timestamp.mp4";

    $list_file = $upload_dir . "videos_to_merge.txt";
    // Merge video parts into one without re-encoding
    shell_exec("ffmpeg -y -f concat -safe 0 -i \"$list_file\" -c copy \"$final_raw_video\"");
    
    // Apply speed change
    $video_speed_filter = 1 / $speed;
    $audio_speed_filter = $speed;
    
    // FFmpeg supports atempo from 0.5 to 2.0. For speeds outside that range, chain filters.
    if ($audio_speed_filter < 0.5) {
        // FFmpeg doesn't support atempo < 0.5 directly, so we chain multiple filters
        $audio_speed_filter = "atempo=0.5,atempo=" . ($speed / 0.5);
    } elseif ($audio_speed_filter > 2.0) {
        // Similarly, for >2.0, break it down
        $audio_speed_filter = "atempo=2.0,atempo=" . ($speed / 2.0);
    } else {
        $audio_speed_filter = "atempo=$audio_speed_filter";
    }
    
    $cmd = "ffmpeg -y -i \"$final_raw_video\" -filter_complex \"[0:v]setpts={$video_speed_filter}*PTS[v];[0:a]{$audio_speed_filter}[a]\" -map \"[v]\" -map \"[a]\" \"$final_video\"";
    shell_exec($cmd);
    
    $video_url = 'Sections/uploads/' . basename($final_video);
    
    $show_footer = true;

    echo "<div class='text-center p-3'>";
    echo "<h4>Final Video:</h4>";
    echo "<video width='640' height='350' controls><source src='$video_url' type='video/mp4'></video><br>";
    echo "<a href='$video_url' id='download' class='btn btn-success mt-2' download>Download Video</a>";
    echo "</div>";
}
  ?>

<!-- <script src="js/Images.js"></script> -->

<script>
  const wrapper = document.getElementById("drop-area");
  const defaultBtn = document.getElementById("default-btn");
  const placeholderText = document.getElementById("placeholder-text");

  function defaultBtnActive() {
    defaultBtn.click();
  }

  // Click & double-click to open file selector
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
    handleFiles(e.dataTransfer.files);
  });

  function handleFiles(files) {
    [...files].forEach(file => {
      if (!file.type.startsWith("image/")) return;

      const reader = new FileReader();
      reader.onload = function (e) {
        // Hide placeholder on first image
        placeholderText.style.display = 'none';

        const previewBox = document.createElement("div");
        previewBox.classList.add("preview");

        const img = document.createElement("img");
        img.src = e.target.result;

        const removeBtn = document.createElement("button");
        removeBtn.classList.add("remove-btn");
        removeBtn.innerHTML = "&times;";
        removeBtn.onclick = () => {
          wrapper.removeChild(previewBox);
          if (wrapper.querySelectorAll('.preview').length === 0) {
            placeholderText.style.display = 'inline';
          }
        };

        previewBox.appendChild(img);
        previewBox.appendChild(removeBtn);
        wrapper.appendChild(previewBox);
      };
      reader.readAsDataURL(file);
    });
  }

  // Progress bar simulation on form submit
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const progressContainer = document.getElementById('progress-container');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const downloadBtn = document.getElementById('download');

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

    if (downloadBtn) {
      downloadBtn.addEventListener('click', () => directDownload.click());
      directDownload.click();
      progressBar.style.width = '100%';
      progressText.textContent = 'Done!';
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
