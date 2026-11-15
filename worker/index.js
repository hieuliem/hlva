import abcData from "../inc/json/ABC.json";
import aData from "../inc/json/A.json";
import bData from "../inc/json/B.json";
import cData from "../inc/json/C.json";
import plData from "../inc/json/pl.json";
import tcData from "../inc/json/tc.json";
import gsData from "../inc/json/gs.json";
import ttData from "../inc/json/tt.json";
import psData from "../inc/json/ps.json";
import stData from "../inc/json/st.json";
import mediaManifest from "../inc/json/media_manifest.json";
import lichCompiled from "../inc/json/lich_compiled.json";
import pageTemplate from "../inc/json/page_template.json";

const DEFAULT_R2_URL = "https://pub-3de53d5d95bd4ddba9c86d03da43133d.r2.dev";

const YEAR_MAP = { A: aData, B: bData, C: cData };
const TC_MAP = { tc: tcData, gs: gsData, tt: ttData, ps: psData, st: stData };
const NAM_ARR = ["A", "B", "C"];
const TC_TABS = [
  ["tc", "Thánh Ca"],
  ["gs", "Giáng Sinh"],
  ["tt", "Tuần Thánh"],
  ["ps", "Phục Sinh"],
  ["st", "Sáng Tác Mới"],
];

const MP3_NAMES = [
  ["TongPhoi.mp3", "Tổng Phối"],
  ["Dan.mp3", "Đàn Đệm"],
  ["Nu.mp3", "Giọng Nữ"],
  ["Nam.mp3", "Giọng Nam"],
  ["Soprano.mp3", "Soprano"],
  ["Alto.mp3", "Alto"],
  ["Tenor.mp3", "Tenor"],
  ["Bass.mp3", "Bass"],
  ["Organ.mp3", "Organ"],
  ["Piano.mp3", "Piano"],
  ["Violin.mp3", "Violin"],
  ["KeyBoard.mp3", "Keyboard"],
  ["Guitar.mp3", "Guitar"],
];

function isMobileRequest(request) {
  const ua = request.headers.get("user-agent") || "";
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile/i.test(ua);
}

function escapeHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

// Parse MM/DD/YYYY into UTC midnight timestamp
function parseMDY(mdyStr) {
  const [m, d, y] = String(mdyStr).split("/").map((n) => parseInt(n, 10));
  return Date.UTC(y, m - 1, d);
}

// Get current date in America/New_York (Richmond, VA) at midnight UTC
function getTodayInfo() {
  const parts = new Intl.DateTimeFormat("en-US", {
    timeZone: "America/New_York",
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
  }).formatToParts(new Date());
  const map = {};
  for (const p of parts) map[p.type] = p.value;
  const y = parseInt(map.year, 10);
  const m = parseInt(map.month, 10);
  const d = parseInt(map.day, 10);
  const todayTs = Date.UTC(y, m - 1, d);
  const dow = new Date(todayTs).getUTCDay(); // 0 = Sunday
  // Match PHP strtotime('last Sunday') and strtotime('next Sunday')
  const lastSunOffset = dow === 0 ? -7 : -dow;
  const nextSunOffset = dow === 0 ? 7 : 7 - dow;
  const lastSundayTs = todayTs + lastSunOffset * 86400000;
  const nextSundayTs = todayTs + nextSunOffset * 86400000;
  const isoToday = `${map.year}-${map.month}-${map.day}`;
  return { todayTs, lastSundayTs, nextSundayTs, isoToday };
}

function inRange(startStr, endStr, targetTs) {
  const s = parseMDY(startStr);
  const e = parseMDY(endStr);
  return targetTs >= s && targetTs <= e;
}

function getCurrentYearLetter(todayTs) {
  for (const [letter, range] of Object.entries(abcData)) {
    if (inRange(range.start, range.end, todayTs)) {
      return letter;
    }
  }
  return "A";
}

function getYearRangeLabel(letter) {
  const d = abcData[letter];
  if (!d) return "NOT SET";
  const sYear = d.start.slice(d.start.lastIndexOf("/") + 1);
  const eYear = d.end.slice(d.end.lastIndexOf("/") + 1);
  return `(${sYear}-${eYear})`;
}

function renderHomePage(request) {
  const mobile = isMobileRequest(request);
  const { todayTs } = getTodayInfo();
  const currLetter = getCurrentYearLetter(todayTs);
  const activeKey = Math.max(0, NAM_ARR.indexOf(currLetter));
  const activeRange = abcData[currLetter] || abcData.A;
  const calYear1 = parseInt(activeRange.end.slice(activeRange.end.lastIndexOf("/") + 1), 10) || 2026;
  const calYear2 = calYear1 + 1;

  let tvdcLis = "";
  for (const n of NAM_ARR) {
    tvdcLis += `<li><a href="#${n}" data="main/pvUtils.php?name=${n}">Phụng Vụ Đáp Ca Năm ${n} ${getYearRangeLabel(n)}</a></li> `;
  }
  tvdcLis += `<li><a href="#3" target="_blank">Lịch Phụng Vụ ${calYear1}</a></li>\n\t<li><a href="#4" target="_blank">\n\t${mobile ? " Lịch Phụng Vụ" : ""} ${calYear2}\n\t</a></li>`;

  let tvdcDivs = "";
  for (const n of NAM_ARR) {
    tvdcDivs += `<div id="${n}"></div>`;
  }
  tvdcDivs += `<div id="3"></div><div id="4"></div>`;

  let tcLis = "";
  let tcDivs = "";
  for (const [k, v] of TC_TABS) {
    tcLis += `<li><a href="#${k}" data="main/pvUtils.php?name=${k}">${v}</a></li> `;
    tcDivs += `<div id="${k}"></div>`;
  }

  const bodyHtml = `
<div id="tvdcTab">
\t<ul>
\t${tvdcLis}
\t</ul>

\t${tvdcDivs}
</div>
<div id="tcTab">
\t<ul>
\t${tcLis}
\t</ul>
\t${tcDivs}
</div>
<div>&nbsp;</div>
<script>
$(document).on('click', 'a[href="#3"]', function() {
   window.location.href = "lich/${calYear1}";
});
$(document).on('click', 'a[href="#4"]', function() {
   window.location.href = "lich/${calYear2}";
});
</script>`;

  const tail = pageTemplate.tail
    .replace("__ACTIVE_TAB_KEY__", String(activeKey))
    .replace("__IS_MOBILE__", mobile ? "true" : "false");

  return new Response(pageTemplate.head + bodyHtml + tail, {
    headers: { "content-type": "text/html; charset=UTF-8" },
  });
}

function printOrdoSection(le, mobile) {
  let m1 = "";
  let m2 = "";
  const m3 = "";
  if (plData[le]) {
    for (const phanle of plData[le]) {
      const folder = `${phanle.dir}/`;
      const typeLower = String(phanle.type).toLowerCase();
      const pl = mobile
        ? `<span class="pl${typeLower}">${phanle.type}:&nbsp;&nbsp;</span>`
        : `<span class="pl${typeLower}"></span>`;
      const html = `${pl}<a class="tvdc" href="${folder}"><span class="cnl_tvdc" id="">${phanle.name}</span></a>`;
      if (phanle.type === "CNL") m1 = html;
      else m2 = html;
    }
  }
  return (
    `<div class="col-md-3">${m1}</div>` +
    `<div class="col-md-2">${m2}</div>` +
    `<div class="clearfix visible-sm-block visible-md-block visible-lg-block">${m3}</div>`
  );
}

function renderTvdcList(letter, mobile) {
  const data = YEAR_MAP[letter];
  if (!data) return "";
  const { lastSundayTs, nextSundayTs } = getTodayInfo();
  const path = `${data.path}/`;
  let done = false;
  let mess = "";

  for (const s of data.list) {
    let tv = s.tv;
    if (!isNaN(Number(tv))) {
      tv = `${data.type} ${s.tv}`;
    }
    const folder = `${path}${s.dir}/`;
    const folderKey = folder.replace(/\/+$/, "");
    const miss = !(folderKey in mediaManifest);

    let dateClss = "d";
    let flashingID1 = "";
    const rowTs = parseMDY(s.date);
    if (!done && rowTs >= lastSundayTs && rowTs <= nextSundayTs) {
      dateClss = "r";
      flashingID1 = 'id="flashingtext1"';
      done = true;
    }

    let pldc = '<span class="pldc"></span>';
    let cls = miss ? 'style="color: silver;"' : "";
    if (mobile) {
      pldc = '<span class="pldc">ĐC:&nbsp;&nbsp;&nbsp;&nbsp;</span>';
      cls = 'style="margin-bottom: 15px;"';
    }

    mess += `<div id="row" ${cls}>`;
    mess += `<div class="col-md-1"><span class="${dateClss}">${s.date}</span></div>`;
    mess += `<div class="col-md-4"><span class="le_tvdc">${s.le}</span></div>`;
    mess += `<div class="col-md-2">${pldc}`;

    if (miss) {
      mess += `<span class="tv_tvdc">${tv}</span>`;
    } else {
      mess += `<a class="tvdc" href="${folder}"><span class="tv_tvdc" ${flashingID1}>${tv}</span></a>`;
    }

    if (Array.isArray(s.baikhac)) {
      for (const bk of s.baikhac) {
        mess += `<br><a class="tvdc" href="${path}${bk.dir}/"><span class="tv_tvdc" >${bk.name}</span></a>`;
      }
    }
    mess += `</div>`;
    mess += printOrdoSection(s.le, mobile);
    mess += `</div>`;
  }
  return mess;
}

function renderTcList(name, mobile, r2BaseUrl) {
  const data = TC_MAP[name];
  if (!data) return "";
  const { todayTs } = getTodayInfo();
  const serURL = `${r2BaseUrl.replace(/\/+$/, "")}/`;
  const path = `${data.path}/`;
  const subpath = data.sub ? `${data.sub}/` : "";
  let mess = "";

  for (const s of data.list) {
    const pdf = `${path}${subpath}${s.dir}/${s.pdf}.pdf`;
    const tg = mobile
      ? '<span class="tacgia">tg:&nbsp;&nbsp;&nbsp;&nbsp;</span>'
      : '<span class="tacgia"></span>';

    mess += '<div id="row">';
    mess += `<div class="col-md-6"><a href="${serURL}${pdf}"><img src="images/pdf_icon.gif" width="12"/></a>`;
    const sDateTs = parseMDY(s.date);
    if (sDateTs >= todayTs && sDateTs <= sDateTs) {
      mess += '&nbsp;&nbsp;&nbsp;<img src="images/new10_e0.gif" style="" width="16"/>';
    }
    if (String(s.pdf).startsWith("BaiThuongKho")) {
      mess += `&nbsp;&nbsp;&nbsp;<a href="${serURL}${pdf}">${s.name}</a></div>`;
    } else {
      mess += `&nbsp;&nbsp;&nbsp;<a class="tvdc" href="${path}${subpath}${s.dir}/">${s.name}</a></div>`;
    }
    if (s.caudau === "&nbsp;") {
      mess += `<div class="col-md-6">${tg}<span class="tgName">${s.tacgia}<span></div>`;
    } else {
      mess += `<div class="col-md-2">${tg}<span class="tgName">${s.tacgia}<span></div>`;
      mess += `<div class="col-md-4 italic">${s.caudau}</div><div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>`;
    }
    mess += "</div>";
  }
  return mess;
}

function renderPvUtils(request, url, r2BaseUrl) {
  const mobile = isMobileRequest(request);
  const { todayTs } = getTodayInfo();
  const name = url.searchParams.get("name") || getCurrentYearLetter(todayTs);

  let html = "";
  if (NAM_ARR.includes(name)) {
    html = renderTvdcList(name, mobile);
  } else {
    html = renderTcList(name, mobile, r2BaseUrl);
  }
  return new Response(html, {
    headers: { "content-type": "text/html; charset=UTF-8" },
  });
}

function buildPlayerHtml(prefix, mp3, label, n) {
  let out = `<div class="col-md-1"></div><div class="col-sm-6 col-md-5">`;
  out += `<div class="audio-player">`;
  out += `<h1>${escapeHtml(label)}</h1>`;
  out += `<audio id="audio-player" src="${escapeHtml(prefix + mp3)}" type="audio/mp3" controls="controls"></audio>`;
  out += `</div></div>`;
  if (n % 2 === 0) {
    out += `<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>`;
  }
  return out;
}

function renderSongMedia(url, r2BaseUrl) {
  const rawDir = (url.searchParams.get("dir") || "").trim();
  const cleanDir = rawDir.replace(/\.\./g, "").replace(/\\/g, "/").replace(/^\/+|\/+$/g, "");
  const songTitle = (url.searchParams.get("title") || "").trim();

  const entry = mediaManifest[cleanDir];
  if (!entry) {
    return new Response(
      `<div style="padding:12px;color:#ef4444;">Không tìm thấy thư mục bài hát: ${escapeHtml(cleanDir)}</div>`,
      { status: 404, headers: { "content-type": "text/html; charset=UTF-8" } }
    );
  }

  const webPrefix = `${r2BaseUrl.replace(/\/+$/, "")}/${cleanDir}/`;
  const mp3s = Array.isArray(entry.mp3) ? [...entry.mp3] : [];
  const tongphoiJPG = Array.isArray(entry.tongphoi) ? entry.tongphoi : [];
  const pdfs = Array.isArray(entry.pdf) ? entry.pdf : [];
  const allJpgs = Array.isArray(entry.jpg) ? entry.jpg : [];

  let out = "";
  let fName = "";

  if (mp3s.length > 0) {
    let n = 1;
    const firstMp3 = mp3s.shift();
    const dotIdx = firstMp3.indexOf(".mp3");
    fName = dotIdx !== -1 ? firstMp3.slice(0, dotIdx) : firstMp3;
    const underIdx = firstMp3.indexOf("_");
    let tv = underIdx !== -1 ? firstMp3.slice(0, underIdx).replace("Tv", "Thánh Vịnh ") : "";
    if (!tv) {
      tv = songTitle || fName;
    }
    out += buildPlayerHtml(webPrefix, firstMp3, tv, n);

    for (const [suffix, label] of MP3_NAMES) {
      const idx = mp3s.findIndex((m) => m.endsWith(suffix));
      if (idx !== -1) {
        const mv = mp3s[idx];
        mp3s.splice(idx, 1);
        out += buildPlayerHtml(webPrefix, mv, label, ++n);
      }
    }
  }

  // toolContainer
  const fbHref = encodeURIComponent(`https://thanhca.chungnhan.org/taphat/${cleanDir}`);
  out += `<div class="toolContainer">`;
  out += `<div class="lyrics"><button id="lyricsBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Main version!</button></div>`;
  out += `<div class="hoaam"><button id="hoaamBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Hòa Âm - Tổng Phối version!</button></div>`;
  out += `<div class="fb"><iframe src="https://www.facebook.com/plugins/share_button.php?href=${fbHref}&layout=button_count&size=small&width=77&height=20&appId" width="77" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe></div>`;
  out += `</div>`;

  if (tongphoiJPG.length > 0) {
    out += `<div id="tongphoi" style="display: none;">`;
    for (const tp of tongphoiJPG) {
      out += `<img src="${escapeHtml(webPrefix + tp)}" width="100%">`;
    }
    out += `</div>`;
  }

  if (pdfs.length > 0) {
    out += `<div class="col-md-1"></div><div id="pdf">`;
    for (const pdf of pdfs) {
      const prefix = `${fName}_`;
      if (fName && pdf.startsWith(prefix) && pdf.endsWith(".pdf")) {
        const subName = pdf.slice(prefix.length, -4);
        out += `<a style="margin-left: 20px;" href="${escapeHtml(webPrefix + pdf)}" target="_blank">${escapeHtml(subName)}</a>`;
      } else {
        out += `<a style="margin-left: 20px;" href="${escapeHtml(webPrefix + pdf)}" target="_blank">${escapeHtml(pdf)}</a>`;
      }
    }
    out += `</div>`;
  }

  let jpgs = [];
  if (fName) {
    jpgs = allJpgs.filter((j) => j.startsWith(`${fName}-`) || j.startsWith(`${fName}_Page_`));
    if (jpgs.length === 0) {
      jpgs = allJpgs.filter((j) => j === `${fName}.jpg`);
    }
    if (jpgs.length === 0) {
      jpgs = allJpgs.filter((j) => j.startsWith(fName));
    }
  }
  if (jpgs.length === 0) {
    jpgs = allJpgs;
  }

  for (const jpg of jpgs) {
    out += `<img src="${escapeHtml(webPrefix + jpg)}" width="100%">`;
  }

  return new Response(out, {
    headers: { "content-type": "text/html; charset=UTF-8" },
  });
}

function renderLich(yearStr) {
  const cal = lichCompiled[yearStr];
  if (!cal) {
    return new Response("Not Found", { status: 404 });
  }
  const { isoToday } = getTodayInfo();
  let flashingSet = false;

  let html = `<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CPK5M5031M"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-CPK5M5031M');
    </script>
\t<meta charset="utf-8" />
\t<meta name="viewport" content="width=device-width, initial-scale=1.0" />
\t<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
\t<title>Lịch Thánh Ca ${cal.year}</title>
\t<meta name="keywords" content="lịch Phụng Vụ, thánh ca, thánh vịnh, đáp ca, tập hát, Kim Long, ca đoàn, seraphim, TV, DC, ĐC, Thánh Vịnh Đáp Ca, tap hat, ca doan, thanh ca" />
\t<meta name="description" content="Lịch Thánh Ca Phụng Vụ Chúa Nhật và Lễ" />
\t<meta name="author" content="Hiếu Liêm">
\t<link rel="icon" type="image/png" href="/favicon.ico">
\t<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
\t<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
\t<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
\t<link type="text/css" rel="stylesheet" href="/lich/style.css" />
</head>
<body>`;

  html += `<div class="two alt-two"><h1>Lịch Thánh Ca ${cal.year}<span>Phụng Vụ Chúa Nhật ${cal.namPV}</span></h1></div>`;
  html += `<table cellpadding="5" cellspacing="1" width="95%" border="2" class="t">`;
  html += `<tr class="tblHeader" align="center"><td width="4%" class="t">Ngày / Tháng</td><td width="21%" align="center">Thứ</td><td width="1%"></td><td width="50%" align="center">Lễ</td></tr>`;

  for (const item of cal.items) {
    let nextCls = "";
    let onCls = "";
    if (item.date === isoToday) {
      onCls = " bgblack";
    } else if (!flashingSet && item.date > isoToday) {
      nextCls = " selected";
      flashingSet = true;
    }

    if (item.monHeader) {
      html += `<tr class="monHeader"><td colspan="2" align="center">${item.monHeader.title}</td><td></td><td align="center">${item.monHeader.desc}</td></tr> `;
    }

    html +=
      `<tr class="${nextCls}">` +
      `<td width="10%" align="center" class="daymon${onCls}">${item.dm}</td>` +
      `<td width="15%" class="${item.clsCN}">${item.wd}</td>` +
      `<td style="background-color: ${item.color}"></td>` +
      `<td width="50%" class="${item.color}">${item.leName}</td></tr>`;
  }

  html += `</table></body></html>`;
  return new Response(html, {
    headers: { "content-type": "text/html; charset=UTF-8" },
  });
}

export default {
  async fetch(request, env) {
    const url = new URL(request.url);
    const path = url.pathname;
    const r2BaseUrl = (env && env.THANHCA_R2_URL) || DEFAULT_R2_URL;

    if (path === "/" || path === "/index.php") {
      return renderHomePage(request);
    }

    if (path === "/main/pvUtils.php") {
      return renderPvUtils(request, url, r2BaseUrl);
    }

    if (path === "/main/songMedia.php") {
      return renderSongMedia(url, r2BaseUrl);
    }

    const lichMatch = path.match(/^\/lich\/(\d{4})(?:\/|\/index\.php)?$/);
    if (lichMatch) {
      return renderLich(lichMatch[1]);
    }

    if (env && env.ASSETS) {
      return env.ASSETS.fetch(request);
    }

    return new Response("Not Found", { status: 404 });
  },
};
