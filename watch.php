<?php
if (!isset($_GET['v'])) {
  include "./404.html";
  exit();
}
$error = false;
include './admin/connection.inc.php';
$sql = "SELECT * FROM `videos` WHERE `short_url` = '{$_GET['v']}' AND `status` = '1'";
$result = mysqli_query($con, $sql);
if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $video_data = $row['video_data'];
  $title = $row['title'];

  $data = json_decode($video_data);
  count($data->sources);
} else {
  $error = true;
  include "./404.html";
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="<?php echo SITE_PATH ?>/assets/css/style.css" />
  <link rel="shortcut icon" href="<?php echo SITE_PATH ?>/assets/images/favicon.png" type="image/x-icon">
  <title><?php echo $title ?></title>
  <!-- S-Tech04 -->
  <!-- www.youtube.com/c/STech04 -->
</head>

<body>
  <div id="stech_video_player" class="stech_video_player" style="--cColor:<?php echo $data->controls[0]->color ?>;--pColor:<?php echo $data->controls[0]->progressColor ?>;">
    <div class="loader"></div>
    <video preload="metadata" id="main-video" poster="<?php echo $data->controls[0]->poster ?>">
    <?php 
     $i=0;
     while ($i < count($data->sources)) {
    ?>
      <source src="<?php echo $data->sources[$i]->src ?>" size="<?php echo $data->sources[$i]->size ?>" type="<?php echo $data->sources[$i]->type ?>">
    <?php 
            $i++;
    }
    ?>
    <?php 
     $i=0;
     while ($i < count($data->subtitles)) {
    ?>
    <track label="<?php echo $data->subtitles[$i]->label ?>" kind="<?php echo $data->subtitles[$i]->kind ?>" src="<?php echo $data->subtitles[$i]->src ?>" srclang="<?php echo $data->subtitles[$i]->srclang ?>">
      <source src="" size="<?php echo $data->sources[$i]->size ?>" type="<?php echo $data->sources[$i]->type ?>">
    <?php 
            $i++;
    }
    ?>
    </video>
    <p class="caption_text"></p>
    <div class="progressAreaTime">0:00</div>

    <div class="controls">
      <?php if ($data->controls[0]->logo != "") {?>
        <img src="<?php echo $data->controls[0]->logo ?>" class="logo">
      <?php } ?>
      <div class="progress-area">
        <canvas class="bufferedBar"></canvas>
        <div class="progress-bar">
          <span></span>
        </div>
      </div>

      <div class="controls-list">
        <div class="controls-left">
          <span class="icon">
            <i class="material-icons fast-rewind">replay_10</i>
          </span>

          <span class="icon">
            <i class="material-icons play_pause">play_arrow</i>
          </span>

          <span class="icon">
            <i class="material-icons fast-forward">forward_10</i>
          </span>

          <span class="icon">
            <i class="material-icons volume">volume_up</i>

            <input type="range" min="0" max="100" value="100" class="volume_range" />
          </span>

          <div class="timer">
            <span class="current">0:00</span> /
            <span class="duration">0:00</span>
          </div>
        </div>

        <div class="controls-right">
          <span class="icon">
            <i class="material-icons auto-play"></i>
          </span>

          <span class="icon">
            <i class="material-icons captionsBtn">closed_caption</i>
          </span>

          <span class="icon">
            <i class="material-icons settingsBtn">settings</i>
          </span>

          <span class="icon">
            <i class="material-icons picture_in_picutre">picture_in_picture_alt</i>
          </span>

          <span class="icon">
            <i class="material-icons fullscreen">fullscreen</i>
          </span>
        </div>
      </div>
    </div>

    <div id="settings">
      <div data-label="settingHome">
        <ul>
          <li data-label="speed">
            <span> Speed </span>
            <span class="material-symbols-outlined icon">
              arrow_forward_ios
            </span>
          </li>
          <li data-label="quality">
            <span> Quality </span>
            <span class="material-symbols-outlined icon">
              arrow_forward_ios
            </span>
          </li>
        </ul>
      </div>
      <div class="playback" data-label="speed" hidden>
        <span>
          <i class="material-symbols-outlined icon back_arrow" data-label="settingHome">
            arrow_back
          </i>
          <span>Playback Speed </span>
        </span>
        <ul>
          <li data-speed="0.25">0.25</li>

          <li data-speed="0.5">0.5</li>

          <li data-speed="0.75">0.75</li>

          <li data-speed="1" class="active">Normal</li>

          <li data-speed="1.25">1.25</li>

          <li data-speed="1.5">1.5</li>

          <li data-speed="1.75">1.75</li>

          <li data-speed="2">2</li>
        </ul>
      </div>
      <div data-label="quality" hidden>
        <span>
          <i class="material-symbols-outlined icon back_arrow" data-label="settingHome">
            arrow_back
          </i>
          <span>Playback Quality </span>
        </span>
        <ul>
          <li data-quality="auto" class="active">auto</li>
        </ul>
      </div>
    </div>
    <div id="captions">
      <div class="caption">
        <span>Select Subtitle</span>
        <ul>

        </ul>
      </div>
    </div>
  </div>
  <script src="<?php echo SITE_PATH ?>/assets/js/script.js"></script>
  <?php
  if ($error == false) {
    include "./assets/php/update_views.php";
  }
  ?>
</body>

</html>