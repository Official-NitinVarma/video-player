<?php
// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";
// exit();
if (!isset($_SESSION['ADMIN_LOGIN']) && !isset($_SESSION['ADMIN_USERNAME']) && !isset($_GET['v']) && !isset($_GET['type']) && $_GET['type'] == "edit") {
  header("location: ./login.php");
  exit();
}else {
  include "./connection.inc.php";
  $sql = "SELECT * FROM `videos` WHERE `short_url` = '{$_GET['v']}' AND `username` = '{$_SESSION['ADMIN_USERNAME']}'";
  $result = mysqli_query($con, $sql);
  if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $video_data = $row['video_data'];
      $title = $row['title'];
      
      $data = json_decode($video_data);
      count($data->sources);
  }else{
    $error = true;
    include "./404.html";
    exit();
  }
}

?>

<!DOCTYPE html>
<!-- Coding By S-Tech04 - youtube.com/STech04 -->
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
  integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" href="./images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="<?php echo SITE_PATH ?>admin/assets/css/style2.css" />
  <title><?php echo $title; ?></title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h1>Edit Embed Video</h1>
    </header>
    <form class="form">
      <input type="text" class="title" spellcheck="false" placeholder="Enter Video Title" value="<?php echo $title; ?>" required/>
      <?php 
     $i=0;
     while ($i < count($data->sources)) {
    ?>
      <div class="source">
        <label>
            Source <i class="fa-solid fa-xmark close"></i>
        </label>
        <input type="text" placeholder="https://example.com/video.mp4" value="<?php echo $data->sources[$i]->src ?>" class="src" required/>
        <input type="text" placeholder="Type" value="video/mp4" value="<?php echo $data->sources[$i]->type ?>" class="type" required/>
        <input type="text" placeholder="Quaility" class="size" value="<?php echo $data->sources[$i]->size ?>" required/>
      </div>
    <?php 
            $i++;
    }
    ?>
      <a class="srcBtn btn">Add Source</a>
      <input type="text" class="thumbnail" spellcheck="false" placeholder="Paste Thumbnail Url" value="<?php echo $data->controls[0]->poster ?>" />
      <input type="text" class="logo_url" spellcheck="false" placeholder="Paste Logo Url" value="<?php echo $data->controls[0]->logo ?>" />
      <label>Control Button Color</label>
      <input type="color" id="controlsColor" value="<?php echo $data->controls[0]->color ?>">
      <label>Progress Bar Color</label>
      <input type="color" id="progressColor" value="<?php echo $data->controls[0]->progressColor ?>">
      <?php 
     $i=0;
     while ($i < count($data->subtitles)) {
    ?>
      <div class="subtitle">
      <label>
          Subtitle <i class="fa-solid fa-xmark close"></i>
      </label>
      <input type="text" placeholder="https://example.com/subtitle.vtt" class="src" value="<?php echo $data->subtitles[$i]->src ?>" required/>
      <input type="text" placeholder="Kind" value="subtitle" class="kind" value="<?php echo $data->subtitles[$i]->kind ?>"  required/>
      <input type="text" placeholder="Label" class="label" value="<?php echo $data->subtitles[$i]->label ?>"  required/>
      <input type="text" placeholder="en" class="srclang" value="<?php echo $data->subtitles[$i]->srclang ?>"  required/>
  </div>
    <?php 
            $i++;
    }
    ?>
      <a class="trBtn btn">Add Caption</a>
      <a class="preBtn btn">Preview Video</a>
      <button class="subBtn" type="submit">Update</button>
      <input type="hidden" class="url_type" value="<?php echo $_GET['type'];?>" readonly>
      <input type="hidden" class="short_url" value="<?php echo $_GET['v'];?>" readonly>
    </form>
  </div>
  <div class="video_preview">
    <h2>Video Preview</h2>
    <iframe src=""frameborder="0"></iframe>
  </div>

  <script src="<?php echo SITE_PATH ?>admin/assets/js/script.js" data-source="<?php echo $video_data; ?>" id="video_data"></script>
</body>

</html>