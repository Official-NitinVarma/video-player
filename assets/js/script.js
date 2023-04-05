// let's select all required tags or elements
const video_player = document.querySelector('#stech_video_player');
const mainVideo = video_player.querySelector("#main-video");
progressAreaTime = video_player.querySelector(".progressAreaTime"),
  controls = video_player.querySelector(".controls"),
  progressArea = video_player.querySelector(".progress-area"),
  bufferedBar = video_player.querySelector(".bufferedBar"),
  progress_Bar = video_player.querySelector(".progress-bar"),
  fast_rewind = video_player.querySelector(".fast-rewind"),
  play_pause = video_player.querySelector(".play_pause"),
  fast_forward = video_player.querySelector(".fast-forward"),
  volume = video_player.querySelector(".volume"),
  volume_range = video_player.querySelector(".volume_range"),
  current = video_player.querySelector(".current"),
  totalDuration = video_player.querySelector(".duration"),
  auto_play = video_player.querySelector(".auto-play"),
  settingsBtn = video_player.querySelector(".settingsBtn"),
  captionsBtn = video_player.querySelector(".captionsBtn"),
  picture_in_picutre = video_player.querySelector(".picture_in_picutre"),
  fullscreen = video_player.querySelector(".fullscreen"),
  settings = video_player.querySelector("#settings"),
  settingHome = video_player.querySelectorAll("#settings [data-label='settingHome'] > ul > li"),
  captions = video_player.querySelector("#captions"),
  caption_labels = video_player.querySelector("#captions ul"),
  playback = video_player.querySelectorAll(".playback li"),
  loader = video_player.querySelector(".loader");


var tracks = video_player.querySelectorAll("track");
if (tracks.length == 0) {
  var tracks = false;
}

if (tracks != false) {
  caption_labels.insertAdjacentHTML(
    "afterbegin",
    '<li data-lable="OFF" class="active">OFF</li>'
  );
  for (let i = 0; i < tracks.length; i++) {
    let trackLabel = `<li data-lable="${tracks[i].label}">${tracks[i].label}</li>`;
    caption_labels.insertAdjacentHTML("beforeend", trackLabel);
  }
}
const caption = video_player.querySelectorAll(".caption li");

// Play video function
function playVideo() {
  play_pause.innerHTML = "pause";
  play_pause.title = "pause";
  video_player.classList.add("playing");
  mainVideo.play();
}

// Pause video function
function pauseVideo() {
  play_pause.innerHTML = "play_arrow";
  play_pause.title = "play";
  video_player.classList.remove("playing");
  mainVideo.pause();
}

play_pause.addEventListener("click", () => {
  const isVideoPaused = video_player.classList.contains("playing");
  isVideoPaused ? pauseVideo() : playVideo();
});

mainVideo.addEventListener("play", () => {
  playVideo();
});

mainVideo.addEventListener("pause", () => {
  pauseVideo();
});

// fast_rewind video function
fast_rewind.addEventListener("click", () => {
  mainVideo.currentTime -= 10;
});

// fast_forward video function
fast_forward.addEventListener("click", () => {
  mainVideo.currentTime += 10;
});

// Load video duration
mainVideo.addEventListener("loadeddata", (e) => {
  let videoDuration = e.target.duration;
  let totalMin = Math.floor(videoDuration / 60);
  let totalSec = Math.floor(videoDuration % 60);

  // if seconds are less then 10 then add 0 at the begning
  totalSec < 10 ? (totalSec = "0" + totalSec) : totalSec;
  totalDuration.innerHTML = `${totalMin} : ${totalSec}`;
});

// Current video duration
mainVideo.addEventListener("timeupdate", (e) => {
  let currentVideoTime = e.target.currentTime;
  let currentMin = Math.floor(currentVideoTime / 60);
  let currentSec = Math.floor(currentVideoTime % 60);
  // if seconds are less then 10 then add 0 at the begning
  currentSec < 10 ? (currentSec = "0" + currentSec) : currentSec;
  current.innerHTML = `${currentMin} : ${currentSec}`;

  let videoDuration = e.target.duration;
  // progressBar width change
  let progressWidth = (currentVideoTime / videoDuration) * 100 + 0.5;
  progress_Bar.style.width = `${progressWidth}%`;
});

// let's update playing video current time on according to the progress bar width

progressArea.addEventListener("pointerdown", (e) => {
  progressArea.setPointerCapture(e.pointerId);
  setTimelinePosition(e);
  progressArea.addEventListener("pointermove", setTimelinePosition);
  progressArea.addEventListener("pointerup", () => {
    progressArea.removeEventListener("pointermove", setTimelinePosition);
  })
});


function setTimelinePosition(e) {
  let videoDuration = mainVideo.duration;
  let progressWidthval = progressArea.clientWidth + 2;
  let ClickOffsetX = e.offsetX;
  mainVideo.currentTime = (ClickOffsetX / progressWidthval) * videoDuration;

  let progressWidth = (mainVideo.currentTime / videoDuration) * 100 + 0.5;
  progress_Bar.style.width = `${progressWidth}%`;

  let currentVideoTime = mainVideo.currentTime;
  let currentMin = Math.floor(currentVideoTime / 60);
  let currentSec = Math.floor(currentVideoTime % 60);
  // if seconds are less then 10 then add 0 at the begning
  currentSec < 10 ? (currentSec = "0" + currentSec) : currentSec;
  current.innerHTML = `${currentMin} : ${currentSec}`;
}

function drawProgress(canvas, buffered, duration) {
  let context = canvas.getContext('2d', { antialias: false });
  context.fillStyle = "#ffffffe6";

  let height = canvas.height;
  let width = canvas.width;
  if (!height || !width) throw "Canva's width or height or not set.";
  context.clearRect(0, 0, width, height);
  for (let i = 0; i < buffered.length; i++) {
    let leadingEdge = buffered.start(i) / duration * width;
    let trailingEdge = buffered.end(i) / duration * width;
    context.fillRect(leadingEdge, 0, trailingEdge - leadingEdge, height)
  }
}

mainVideo.addEventListener('progress', () => {
  drawProgress(bufferedBar, mainVideo.buffered, mainVideo.duration);
})

mainVideo.addEventListener('waiting', () => {
  loader.style.display = "block";
})

mainVideo.addEventListener('canplay', () => {
  loader.style.display = "none";
})


// change volume
function changeVolume() {
  mainVideo.volume = volume_range.value / 100;
  if (volume_range.value == 0) {
    volume.innerHTML = "volume_off";
  } else if (volume_range.value < 40) {
    volume.innerHTML = "volume_down";
  } else {
    volume.innerHTML = "volume_up";
  }
}

function muteVolume() {
  if (volume_range.value == 0) {
    volume_range.value = 80;
    mainVideo.volume = 0.8;
    volume.innerHTML = "volume_up";
  } else {
    volume_range.value = 0;
    mainVideo.volume = 0;
    volume.innerHTML = "volume_off";
  }
}

volume_range.addEventListener("change", () => {
  changeVolume();
});

volume.addEventListener("click", () => {
  muteVolume();
});

// Update progress area time and display block on mouse move
progressArea.addEventListener("mousemove", (e) => {
  let progressWidthval = progressArea.clientWidth + 2;
  let x = e.offsetX;
  let videoDuration = mainVideo.duration;
  let progressTime = Math.floor((x / progressWidthval) * videoDuration);
  let currentMin = Math.floor(progressTime / 60);
  let currentSec = Math.floor(progressTime % 60);
  progressAreaTime.style.setProperty("--x", `${x}px`);
  progressAreaTime.style.display = "block";

  // if seconds are less then 10 then add 0 at the begning
  currentSec < 10 ? (currentSec = "0" + currentSec) : currentSec;
  progressAreaTime.innerHTML = `${currentMin} : ${currentSec}`;
});

progressArea.addEventListener("mouseleave", () => {
  progressAreaTime.style.display = "none";
});

// Auto play
auto_play.addEventListener("click", () => {
  auto_play.classList.toggle("active");
  if (auto_play.classList.contains("active")) {
    auto_play.title = "Autoplay is on";
  } else {
    auto_play.title = "Autoplay is off";
  }
});

mainVideo.addEventListener("ended", () => {
  if (auto_play.classList.contains("active")) {
    playVideo();
  } else {
    play_pause.innerHTML = "replay";
    play_pause.title = "Replay";
  }
});

// Picture in picture

picture_in_picutre.addEventListener("click", () => {
  mainVideo.requestPictureInPicture();
});

// Full screen function

fullscreen.addEventListener("click", () => {
  if (!video_player.classList.contains("openFullScreen")) {
    video_player.classList.add("openFullScreen");
    fullscreen.innerHTML = "fullscreen_exit";
    video_player.requestFullscreen();
  } else {
    video_player.classList.remove("openFullScreen");
    fullscreen.innerHTML = "fullscreen";
    document.exitFullscreen();
  }
});

// Open settings
settingsBtn.addEventListener("click", () => {
  settings.classList.toggle("active");
  settingsBtn.classList.toggle("active");
  if (
    captionsBtn.classList.contains("active") ||
    captions.classList.contains("active")
  ) {
    captions.classList.remove("active");
    captionsBtn.classList.remove("active");
  }
});
// Open caption
captionsBtn.addEventListener("click", () => {
  captions.classList.toggle("active");
  captionsBtn.classList.toggle("active");
  if (
    settingsBtn.classList.contains("active") ||
    settings.classList.contains("active")
  ) {
    settings.classList.remove("active");
    settingsBtn.classList.remove("active");
  }
});

// Playback Rate

playback.forEach((event) => {
  event.addEventListener("click", () => {
    removeActiveClasses(playback);
    event.classList.add("active");
    let speed = event.getAttribute("data-speed");
    mainVideo.playbackRate = speed;
  });
});

caption.forEach((event) => {
  event.addEventListener("click", () => {
    removeActiveClasses(caption);
    event.classList.add("active");
    changeCaption(event);
    caption_text.innerHTML = "";
  });
});

let track = mainVideo.textTracks;

function changeCaption(lable) {
  let trackLable = lable.getAttribute("data-track");
  for (let i = 0; i < track.length; i++) {
    track[i].mode = "disabled";
    if (track[i].label == trackLable) {
      track[i].mode = "showing";
    }
  }
}

const settingDivs = video_player.querySelectorAll('#settings > div');
const settingBack = video_player.querySelectorAll('#settings > div .back_arrow');
const quality_ul = video_player.querySelector("#settings > [data-label='quality'] ul");
const qualities = video_player.querySelectorAll("source[size]");

qualities.forEach(event => {
  let quality_html = `<li data-quality="${event.getAttribute('size')}">${event.getAttribute('size')}p</li>`;
  quality_ul.insertAdjacentHTML('afterbegin', quality_html);
})

const quality_li = video_player.querySelectorAll("#settings > [data-label='quality'] ul > li");
quality_li.forEach((event) => {
  event.addEventListener('click', (e) => {
    let quality = event.getAttribute('data-quality');
    removeActiveClasses(quality_li);
    event.classList.add('active');
    qualities.forEach(event => {
      if (event.getAttribute('size') == quality) {
        let video_current_duration = mainVideo.currentTime;
        let video_source = event.getAttribute('src');
        mainVideo.src = video_source;
        mainVideo.currentTime = video_current_duration;
        playVideo();
      }
    })
  })
})

settingBack.forEach((event) => {
  event.addEventListener('click', (e) => {
    let setting_label = e.target.getAttribute('data-label');
    for (let i = 0; i < settingDivs.length; i++) {
      if (settingDivs[i].getAttribute('data-label') == setting_label) {
        settingDivs[i].removeAttribute('hidden');
      } else {
        settingDivs[i].setAttribute('hidden', "");
      }
    }
  })
})

settingHome.forEach((event) => {
  event.addEventListener('click', (e) => {
    let setting_label = e.target.getAttribute('data-label');
    for (let i = 0; i < settingDivs.length; i++) {
      if (settingDivs[i].getAttribute('data-label') == setting_label) {
        settingDivs[i].removeAttribute('hidden');
      } else {
        settingDivs[i].setAttribute('hidden', "");
      }
    }
  })
})


function removeActiveClasses(e) {
  e.forEach((event) => {
    event.classList.remove("active");
  });
}

let caption_text = video_player.querySelector(".caption_text");
for (let i = 0; i < track.length; i++) {
  track[i].addEventListener("cuechange", () => {
    if (track[i].mode === "showing") {
      if (track[i].activeCues[0]) {
        let span = `<span><mark>${track[i].activeCues[0].text}</mark></span>`;
        caption_text.innerHTML = span;
      } else {
        caption_text.innerHTML = "";
      }
    }
  });
}

//  blob url
let mainVideoSources = mainVideo.querySelectorAll("source");
for (let i = 0; i < mainVideoSources.length; i++) {
  let videoUrl = mainVideoSources[i].src;
  blobUrl(mainVideoSources[i], videoUrl);
}
function blobUrl(video, videoUrl) {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", videoUrl);
  xhr.responseType = "arraybuffer";
  xhr.onload = (e) => {
    let blob = new Blob([xhr.response]);
    let url = URL.createObjectURL(blob);
    video.src = url;
  };
  xhr.send();
}

mainVideo.addEventListener("contextmenu", (e) => {
  e.preventDefault();
});

// Mouse move controls
video_player.addEventListener("mouseenter", () => {
  controls.classList.add("active");
  if (tracks.length != 0) {
    caption_text.classList.remove("active");
  }
});

video_player.addEventListener("mouseleave", () => {
  if (video_player.classList.contains("playing")) {
    if (
      settingsBtn.classList.contains("active") ||
      captionsBtn.classList.contains("active")
    ) {
      controls.classList.add("active");
    } else {
      controls.classList.remove("active");
      if (tracks.length != 0) {
        caption_text.classList.add("active");
      }
    }
  } else {
    controls.classList.add("active");
  }
});

if (video_player.classList.contains("playing")) {
  if (
    settingsBtn.classList.contains("active") ||
    captionsBtn.classList.contains("active")
  ) {
    controls.classList.add("active");
  } else {
    controls.classList.remove("active");
    if (tracks.length != 0) {
      caption_text.classList.add("active");
    }
  }
} else {
  controls.classList.add("active");
}


mainVideo.addEventListener('click', () => {
  const isVideoPlaying = video_player.classList.contains("playing");
  isVideoPlaying ? pauseVideo() : playVideo();
  if (
    settingsBtn.classList.contains("active")
  ) {
    settingsBtn.click();
  }
  if (
    captionsBtn.classList.contains("active")
  ) {
    captionsBtn.click();
  }
})
mainVideo.addEventListener('dblclick', () => {
  fullscreen.click();
})
// keyboard events
window.addEventListener('keydown', (e) => {
  if (e.keyCode == "32") {
    const isVideoPlaying = video_player.classList.contains("playing");
    isVideoPlaying ? pauseVideo() : playVideo();
  }
  if (e.keyCode == "37") {
    mainVideo.currentTime -= 5;
  }
  if (e.keyCode == "39") {
    mainVideo.currentTime += 5;
  }
  if (e.keyCode == "38") {
    volume_range.value = parseInt(volume_range.value) + 5;
    changeVolume();
  }
  if (e.keyCode == "40") {
    volume_range.value -= 5;
    changeVolume();
  }
})


if (tracks == false) {
  caption_labels.remove();
  captions.remove();
  captionsBtn.parentNode.remove();
}  
