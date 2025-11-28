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
    return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//player.bilibili.com/player.html?bvid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
  } else {
    return '<iframe class="bilibili w-full min-h-60 md:min-h-120 rounded-box" src="//player.bilibili.com/player.html?aid=' . $content . '" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="true"> </iframe>';
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
