<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: https://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
$config = array();

// Key redis
define('CONVERT_VIDEO_1',REDIS_PREFIX.'convert_video_1'); // convert video thành SD: 720x480
define('CONVERT_VIDEO_2',REDIS_PREFIX.'convert_video_2'); // convert video thành HD: 1080x720
define('CONVERT_VIDEO_3',REDIS_PREFIX.'convert_video_3'); // convert video thành HD: 1920x1080
