<?php
require_once __DIR__ . '/shortcode.lib.php';

function shortcode_notice($atts, $content = '')
{

  return "<div role='alert' class='alert alert-info '>
  <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
  </svg>
  <span>" . $content . "</span>
</div>";
}

function shortcode_alert($atts, $content = '')
{

  return "<div role='alert' class='alert  '>
  <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
  </svg>
  <span>" . $content . "</span>
</div>";
}

function shortcode_error($atts, $content = '')
{

  return "<div role='alert' class='alert alert-error'>
  <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' class='h-6 w-6 shrink-0 stroke-current'>
    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path>
  </svg>
  <span>" . $content . "</span>
</div>";
}

function shortcode_bili($atts, $content = '')
{
  if (preg_match('/[a-zA-Z]/', $content)) {
    return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//www.bilibili.com/blackboard/html5mobileplayer.html?bvid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
  } else {
    return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//www.bilibili.com/blackboard/html5mobileplayer.html?aid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
  }
}

add_shortcode('bili', 'shortcode_bili');

add_shortcode('notice', 'shortcode_notice');
add_shortcode('alert', 'shortcode_alert');
add_shortcode('error', 'shortcode_error');

//Aplayer本地音乐播放器
function shortcode_aplayer($atts, $content = '')
{
  $args = shortcode_atts(array(
    'id' => '',
    'artist' => '',
    'url' => '',
    'pic' => ''
  ), $atts);
  return "<div id='aplayer-" . $args['id'] . "'></div>
  <script data-swup-reload-script>
  (function(){
    var el = document.getElementById('aplayer-" . $args["id"] . "');
    if(!el) return;
    var aplayer" . $args["id"] . " = new APlayer({
      container: el,
      audio: [{
          name: '" . $content . "',
          artist: '" . $args['artist'] . "',
          url: '" . $args['url'] . "',
          cover: '" . $args['pic'] . "'
      }]
    });
  })();
  </script>
  ";
}

add_shortcode('aplayer', 'shortcode_aplayer');

//Aplayer MetingJS
//单曲
function shortcode_aplayer_meting_single($atts, $content = '')
{
  $args = shortcode_atts(array(
    'id' => ''
  ), $atts);
  $uid = $args['id'] ?: uniqid();
  $baseApi = Get::Options('MetingApiUrl', false);
  $api = $baseApi ? ($baseApi . '?type=song&id=' . urlencode($content)) : '';
  $audio = [];
  if ($api) {
    $resp = @file_get_contents($api);
    if ($resp === false && function_exists('curl_init')) {
      $ch = curl_init($api);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_USERAGENT, 'TTDF-APlayer');
      $resp = curl_exec($ch);
      curl_close($ch);
    }
    if ($resp) {
      $decoded = json_decode($resp, true);
      if (is_array($decoded) && !empty($decoded)) {
        $first = $decoded[0];
        $audio = [
          'name' => (string)($first['name'] ?? ''),
          'artist' => (string)($first['artist'] ?? ''),
          'url' => (string)($first['url'] ?? ''),
          'cover' => (string)($first['pic'] ?? ''),
          'lrc' => (string)($first['lrc'] ?? '')
        ];
      }
    }
  }
  $audioJson = json_encode($audio ? [$audio] : [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  return "<div id='aplayer-meting-" . $uid . "'></div>
  <script data-swup-reload-script>
  (function(){
    var el = document.getElementById('aplayer-meting-" . $uid . "');
    if(!el) return;
    var aplayer" . $uid . " = new APlayer({
      container: el,
      lrcType: 3,
      audio: " . $audioJson . "
    });
  })();
  </script>
  ";
}

//列表
function shortcode_aplayer_meting_list($atts, $content = '')
{
  $args = shortcode_atts(array(
    'id' => '',
  ), $atts);
  $uid = $args['id'] ?: uniqid();
  $baseApi = Get::Options('MetingApiUrl', false);
  $api = $baseApi ? ($baseApi . '?type=playlist&id=' . urlencode($content)) : '';
  $list = [];
  if ($api) {
    $resp = @file_get_contents($api);
    if ($resp === false && function_exists('curl_init')) {
      $ch = curl_init($api);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_USERAGENT, 'TTDF-APlayer');
      $resp = curl_exec($ch);
      curl_close($ch);
    }
    if ($resp) {
      $decoded = json_decode($resp, true);
      if (is_array($decoded) && !empty($decoded)) {

        $i = 0;
        foreach ($decoded as $item) {
          $list[] = [
            'name' => (string)($item['name'] ?? ''),
            'artist' => (string)($item['artist'] ?? ''),
            'url' => (string)($item['url'] ?? ''),
            'cover' => (string)($item['pic'] ?? ''),
            'lrc' => (string)($item['lrc'] ?? ''),

          ];
          $i++;
        }
      }
    }
  }
  $listJson = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  return "<div id='aplayer-meting-list-" . $uid . "'></div>
  <script data-swup-reload-script>
  (function(){
    var el = document.getElementById('aplayer-meting-list-" . $uid . "');
    if(!el) return;
    var list = " . $listJson . ";
    if(!list.length) return;
    var aplayer" . $uid . " = new APlayer({
      container: el,
      lrcType: 3,
      audio: list,
    });
  })();
  </script>
  ";
}

add_shortcode('meting_single', 'shortcode_aplayer_meting_single');
add_shortcode('meting_list', 'shortcode_aplayer_meting_list');

//折叠框
function shortcode_collapse($atts, $content = '')
{
  $args = shortcode_atts(array(
    'title' => ''
  ), $atts);
  $uid = uniqid();
  return "<div tabindex='" . $uid . "' class='collapse collapse-arrow bg-base-100 border-base-300 border'>
                    <div class='collapse-title font-semibold'>" . $args['title'] . "</div>
                    <div class='collapse-content text-sm'>
                        " . $content . "
                    </div>
                </div>
  ";
}
add_shortcode('collapse', 'shortcode_collapse');

//相册
function shortcode_photos($atts, $content = '')
{
  // 清理内容中的HTML标签，防止自动分段干扰
  $content = strip_tags($content);
  $content = trim($content);

  if (empty($content)) return '';

  $loadingImg = Get::Options('loadingImg', false);

  // 分割照片
  $photos_raw = explode('###', $content);
  $uid = uniqid('photos-');
  // 初始输出一个普通容器，JS 将接管布局
  // 初始设为 invisible 以避免布局闪烁，或者就让它默认堆叠
  $html = '<div id="' . $uid . '" class="photos-gallery w-full">';

  foreach ($photos_raw as $photo_raw) {
    $photo_raw = trim($photo_raw);
    if (empty($photo_raw)) continue;

    // 分割描述和URL
    $parts = explode(',', $photo_raw);

    // 确保至少有描述和URL（或者容错）
    if (count($parts) < 2) {
      // 尝试容错：如果没有逗号，整体作为URL，描述为空
      if (count($parts) == 1 && !empty($parts[0])) {
        $url = trim($parts[0]);
        $desc = '';
      } else {
        continue;
      }
    } else {
      // 假设最后一部分是URL，前面是描述
      $url = trim(array_pop($parts));
      $desc = trim(implode(',', $parts));
    }

    // 生成HTML
    $html .= '<div class="photo-item overflow-hidden rounded-box relative w-full h-auto group">';
    $html .= '<img data-src="' . $url . '" src="' . $loadingImg . '" alt="' . $desc . '" title="' . $desc . '" class="lightcover m-0 lazyload blur-up cursor-zoom-in" style="margin: auto;" />';
    if (!empty($desc)) {
      $html .= '<div class="photo-desc absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs p-1 text-center backdrop-blur-sm">' . $desc . '</div>';
    }
    $html .= '</div>';
  }
  $html .= '</div>';

  // 添加 JS 脚本进行 Flex Column 布局
  $html .= "<script data-swup-reload-script>
  (function() {
      var container = document.getElementById('$uid');
      if (!container) return;
      
      var lastCols = 0;
      
      function updateLayout() {
          var width = window.innerWidth;
          var cols = width >= 768 ? 4 : 2; // 桌面4列，移动2列
          
          if (cols === lastCols) return;
          lastCols = cols;
          
          // 获取所有 photo-item
          // 注意：如果已经分栏过，items 可能分散在子 div 中，所以要用 querySelectorAll 从 container 及其子元素中找
          var items = Array.from(container.getElementsByClassName('photo-item'));
          if (items.length === 0) return;
          
          // 确保按原始顺序排序
          if (!items[0].hasAttribute('data-index')) {
              items.forEach((item, index) => item.setAttribute('data-index', index));
          }
          items.sort((a, b) => parseInt(a.getAttribute('data-index')) - parseInt(b.getAttribute('data-index')));
          
          // 清空容器
          container.innerHTML = '';
          
          // 创建列容器
          var columns = [];
          for(var i=0; i<cols; i++) {
              var col = document.createElement('div');
              col.className = 'flex flex-col gap-2 flex-1 min-w-0'; // flex-1 均分宽度，gap-2 垂直间距
              columns.push(col);
          }
          
          // 分发 items 到列容器
          items.forEach((item, index) => {
              columns[index % cols].appendChild(item);
          });
          
          // 将列容器添加到主容器
          container.className = 'photos-gallery flex flex-row gap-2 items-start w-full';
          columns.forEach(col => container.appendChild(col));
      }
      
      // 初始执行
      updateLayout();
      
      // 监听 resize
      var timeout;
      window.addEventListener('resize', function() {
          clearTimeout(timeout);
          timeout = setTimeout(updateLayout, 100);
      });
  })();
  </script>";

  return $html;
}

add_shortcode('photos', 'shortcode_photos');
