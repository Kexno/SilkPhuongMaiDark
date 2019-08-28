<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
</head>
<body onload="loadMoxman()">
<script src="<?php echo $this->templates_assets;?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo $this->templates_assets;?>plugins/moxiemanager/js/moxman.loader.min.js"></script>
<script src="<?php echo $this->templates_assets;?>js/pages/media.js"></script>
</body>
</html>