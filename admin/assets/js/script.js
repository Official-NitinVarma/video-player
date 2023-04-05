const wrapper = document.querySelector(".wrapper"),
  src = wrapper.querySelector(".form .src"),
  thumbnail = wrapper.querySelector(".form .thumbnail"),
  progressColor = wrapper.querySelector(".form #progressColor"),
  controlsColor = wrapper.querySelector(".form #controlsColor"),
  logo = wrapper.querySelector(".form .logo_url"),
  previewBtn = wrapper.querySelector(".form .btn.preBtn"),
  addtrack = wrapper.querySelector(".form .btn.trBtn"),
  addSrc = wrapper.querySelector(".form .btn.srcBtn");
const iframe = document.querySelector("iframe");
const form = wrapper.querySelector("form");
const site_path = wrapper.querySelector(".site_path");

String.prototype.toHtmlEntities = function () {
  return this.replace(/./gm, function (s) {
    return s.match(/[a-z0-9\s]+/i) ? s : "&#" + s.charCodeAt(0) + ";";
  });
};

function addNew() {
  addtrack.insertAdjacentHTML(
    "beforebegin",
    `<div class="subtitle">
      <label>
          Subtitle <i class="fa-solid fa-xmark close"></i>
      </label>
      <input type="text" placeholder="https://example.com/subtitle.vtt" class="src" required/>
      <input type="text" placeholder="Kind" value="subtitle" class="kind" required/>
      <input type="text" placeholder="Label" class="label" required/>
      <input type="text" placeholder="en" class="srclang" required/>
  </div>`
  );
  close();
}
addtrack.addEventListener("click", addNew);

function addNewSrc() {
  addSrc.insertAdjacentHTML(
    "beforebegin",
    `<div class="source">
      <label>
          Source <i class="fa-solid fa-xmark close"></i>
      </label>
      <input type="text" placeholder="https://example.com/video.mp4" class="src" required/>
      <input type="text" placeholder="Type" value="video/mp4" class="type" required/>
      <input type="text" placeholder="Quaility" class="size" required/>
  </div>`
  );
}
addSrc.addEventListener("click", addNewSrc);

function fun() {
  let subtitle_arr = new Set();
  let sources_arr = new Set();
  let subtitles = document.querySelectorAll(".subtitle");
  for (let i = 0; i < subtitles.length; i++) {
    let src = subtitles[i].querySelector(".src").value;
    let kind = subtitles[i].querySelector(".kind").value;
    let label = subtitles[i].querySelector(".label").value;
    let srclang = subtitles[i].querySelector(".srclang").value;
    subtitle_arr.add(createObject(src, kind, label, srclang));
  }

  let sources = document.querySelectorAll(".source");
  for (let i = 0; i < sources.length; i++) {
    let src = sources[i].querySelector(".src").value;
    let type = sources[i].querySelector(".type").value;
    let size = sources[i].querySelector(".size").value;
    sources_arr.add(srcObject(src, type, size));
  }
  let controls = [
    {
      poster: thumbnail.value,
      color: controlsColor.value,
      progressColor: progressColor.value,
      logo: logo.value,
    },
  ];

  const embed = createEmbed(sources_arr, subtitle_arr, controls);
  const embeded = JSON.stringify(embed);
  const embeded2 = window.btoa(JSON.stringify(embed));
  return {
    embeded: embeded,
    embeded2: embeded2
  };
}

function addGlobalEventListener(type, selector, callback) {
  document.addEventListener(type, event => {
      if (event.target.matches(selector)) callback(event)
  })
}
addGlobalEventListener('click',".close",element=>{
  element.target.addEventListener("click", (e) => {
    e.target.parentNode.parentNode.remove();
  });
})

form.addEventListener("submit", function (e) {
  e.preventDefault();
  let code = fun();
  generateCode(code.embeded);
});

function preview() {
  let embeded = fun();
  iframe.src = `${site_path.value}preview/index.html?v=${embeded.embeded2}`;
  iframe.parentNode.style.display = "flex";
}

previewBtn.addEventListener("click", preview);

function createEmbed([...sources], [...subtitle], [...controls]) {
  const j = {};
  j.sources = [...sources];
  j.subtitles = [...subtitle];
  j.controls = [...controls];
  return j;
}
function createObject(src, kind, label, srclang) {
  const k = {};
  k.src = src;
  k.kind = kind;
  k.label = label;
  k.srclang = srclang;
  return k;
}
function srcObject(src, type, size) {
  const l = {};
  l.src = src;
  l.type = type;
  l.size = size;
  return l;
}
function generateCode(code) {
  let title = document.querySelector('input.title');
  let xhr = new XMLHttpRequest();
  xhr.open("POST", `${site_path.value}admin/assets/php/embed.php`, true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        let video_preview = document.querySelector(".video_preview");
        let iframe_p_html = `<div class="iframe_code">
                <label>
                  Copy Code <i class="fa-solid fa-xmark close"></i>
                </label>
                <pre></pre>
              </div>`;
        video_preview.insertAdjacentHTML("afterend", iframe_p_html);
        const getCode = document.querySelector(".iframe_code pre");
        let html = data;
        getCode.innerHTML = html.toHtmlEntities();
        getCode.parentNode.style.display = "flex";
      }
    }
  }

  const url_type = document.querySelector('.url_type').value;
  if (url_type == "create") {
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("type=" + url_type + "&data=" + code + "&title=" + title.value); 
  }else if(url_type == "edit"){
    let short_url = document.querySelector('.short_url').value;
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("type=" + url_type + "&v=" + short_url + "&data=" + code + "&title=" + title.value);  
  }

}