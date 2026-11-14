<?php
// Centralized Song Media Loader for Thánh Ca Online
// Supports external media directory (../../taphat_media/) with fallback to ../taphat/
$rawDir = isset($_GET['dir']) ? trim($_GET['dir'], "/\\") : '';
// Prevent directory traversal outside allowed roots
$cleanDir = str_replace(['..', '\\'], ['', '/'], $rawDir);
$songTitle = isset($_GET['title']) ? trim($_GET['title']) : '';

$externalFsRoot = realpath(__DIR__ . '/../../taphat_media');
$internalFsRoot = realpath(__DIR__ . '/../taphat');

$targetFsDir = null;
$webPrefix = '';

if ($externalFsRoot && is_dir($externalFsRoot . '/' . $cleanDir)) {
    $targetFsDir = $externalFsRoot . '/' . $cleanDir;
    $webPrefix = '../taphat_media/' . $cleanDir . '/';
} elseif ($internalFsRoot && is_dir($internalFsRoot . '/' . $cleanDir)) {
    $targetFsDir = $internalFsRoot . '/' . $cleanDir;
    $webPrefix = 'taphat/' . $cleanDir . '/';
}

if (!$targetFsDir) {
    http_response_code(404);
    echo '<div style="padding:12px;color:#ef4444;">Không tìm thấy thư mục bài hát: ' . htmlspecialchars($cleanDir) . '</div>';
    exit;
}

chdir($targetFsDir);

$mp3Names = array(
    "TongPhoi.mp3" => "Tổng Phối",
    "Dan.mp3"      => "Đàn Đệm",
    "Nu.mp3"       => "Giọng Nữ",
    "Nam.mp3"      => "Giọng Nam",
    "Soprano.mp3"  => "Soprano",
    "Alto.mp3"     => "Alto",
    "Tenor.mp3"    => "Tenor",
    "Bass.mp3"     => "Bass",
    "Organ.mp3"    => "Organ",
    "Piano.mp3"    => "Piano",
    "Violin.mp3"   => "Violin",
    "KeyBoard.mp3" => "Keyboard",
    "Guitar.mp3"   => "Guitar"
);

function endsWithStr($haystack, $needle) {
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function buildPlayerHtml($p, $mp3, $label, $n) {
    echo '<div class="col-md-1"></div><div class="col-sm-6 col-md-5">';
    echo '<div class="audio-player">';
    echo '<h1>' . htmlspecialchars($label) . '</h1>';
    echo '<audio id="audio-player" src="' . htmlspecialchars($p . $mp3) . '" type="audio/mp3" controls="controls"></audio>';
    echo '</div></div>';
    if ($n % 2 == 0) {
        echo '<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';
    }
}

$mp3s = glob("*.mp3");
$fName = '';

if (!empty($mp3s)) {
    $n = 1;
    $fName = strstr($mp3s[0], '.mp3', true);
    $TV = str_replace("Tv", "Thánh Vịnh ", strstr($mp3s[0], '_', true));
    if (empty($TV)) {
        $TV = !empty($songTitle) ? $songTitle : $fName;
    }
    buildPlayerHtml($webPrefix, $mp3s[0], $TV, $n);
    unset($mp3s[0]);

    foreach ($mp3Names as $k => $v) {
        foreach ($mp3s as $mk => $mv) {
            if (endsWithStr($mv, $k)) {
                buildPlayerHtml($webPrefix, $mv, $v, ++$n);
                unset($mp3s[$mk]);
                break;
            }
        }
    }
}

// Optional tongphoi (Hòa Âm - Tổng Phối) section & Facebook share
echo '<div class="toolContainer">';
echo '<div class="lyrics"><button id="lyricsBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Main version!</button></div>';
echo '<div class="hoaam"><button id="hoaamBtn" type="button" style="margin-left: 20px; margin-top: -10px;">Click to View Hòa Âm - Tổng Phối version!</button></div>';
echo '<div class="fb"><iframe src="https://www.facebook.com/plugins/share_button.php?href=' . urlencode('https://thanhca.chungnhan.org/taphat/' . $cleanDir) . '&layout=button_count&size=small&width=77&height=20&appId" width="77" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe></div>';
echo '</div>';

if (is_dir("tongphoi")) {
    $tongphoiJPG = glob("tongphoi/*.jpg");
    if (!empty($tongphoiJPG)) {
        echo '<div id="tongphoi" style="display: none;">';
        foreach ($tongphoiJPG as $tp) {
            echo '<img src="' . htmlspecialchars($webPrefix . $tp) . '" width="100%">';
        }
        echo '</div>';
    }
}

// Optional PDF downloads
$pdfs = glob("*.pdf");
if (!empty($pdfs)) {
    echo '<div class="col-md-1"></div><div id="pdf">';
    foreach ($pdfs as $pdf) {
        if ($fName !== '' && preg_match('/' . preg_quote($fName, '/') . '_(.*?).pdf/', $pdf, $match) == 1) {
            echo '<a style="margin-left: 20px;" href="' . htmlspecialchars($webPrefix . $pdf) . '" target="_blank">' . htmlspecialchars($match[1]) . '</a>';
        } else {
            echo '<a style="margin-left: 20px;" href="' . htmlspecialchars($webPrefix . $pdf) . '" target="_blank">' . htmlspecialchars($pdf) . '</a>';
        }
    }
    echo '</div>';
}

// Sheet music JPG images
$jpgs = [];
if ($fName !== '') {
    $jpgs = array_merge(glob($fName . "-*.jpg"), glob($fName . "_Page_*.jpg"));
    if (empty($jpgs)) {
        $jpgs = glob($fName . ".jpg");
    }
    if (empty($jpgs)) {
        $jpgs = glob($fName . "*.jpg");
    }
}
if (empty($jpgs)) {
    $jpgs = glob("*.jpg");
}

foreach ($jpgs as $jpg) {
    echo '<img src="' . htmlspecialchars($webPrefix . $jpg) . '" width="100%">';
}
