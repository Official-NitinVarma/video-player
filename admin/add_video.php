<?php
include "./connection.inc.php";
if (!isset($_SESSION['ADMIN_LOGIN']) && !isset($_SESSION['ADMIN_USERNAME'])) {
  header("location: ./login.php");
  exit();
}

?>

<!DOCTYPE html>
<!-- Coding By S-Tech04 - youtube.com/STech04 -->
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <title>Embed Code Generator | S-Tech04</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
    integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="<?php echo SITE_PATH ?>admin/assets/css/style2.css" />
</head>

<body>
  <div class="wrapper">
    <header>
      <h1>Embed Video</h1>
    </header>
    <form class="form">
      <input type="text" class="title" spellcheck="false" placeholder="Enter Video Title" required/>
      <div class="source">
        <label>
            Source
        </label>
        <input type="text" placeholder="https://example.com/video.mp4" class="src" required/>
        <input type="text" placeholder="Type" value="video/mp4" class="type" required/>
        <input type="text" placeholder="Quaility" class="size" required/>
      </div>
      <a class="srcBtn btn">Add Source</a>
      <input type="text" class="thumbnail" spellcheck="false" placeholder="Paste Thumbnail Url" />
      <input type="text" class="logo_url" spellcheck="false" placeholder="Paste Logo Url" />
      <label>Control Button Color</label>
      <input type="color" id="controlsColor" value="#ffffff">
      <label>Progress Bar Color</label>
      <input type="color" id="progressColor" value="#ffae00">
      <a class="trBtn btn">Add Caption</a>
      <a class="preBtn btn">Preview Video</a>
      <button class="subBtn" type="submit">Generate Embed Code</button>
      <input type="hidden" class="url_type" value="<?php echo $_GET['type'] ?>">
      <input type="hidden" class="site_path" value="<?php echo SITE_PATH ?>">
    </form>
  </div>
  <div class="video_preview">
    <h2>Video Preview</h2>
    <iframe src=""frameborder="0"></iframe>
  </div>

  <script src="<?php echo SITE_PATH ?>admin/assets/js/script.js"></script>
</body>

</html>