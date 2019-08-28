<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('getImageThumb')) {
    function getImageThumb($image = '',$width = '',$height= '', $crop = true,$quality='100%'){
        if(is_array(json_decode($image))){
            $imageArray = json_decode($image);
            $image = $imageArray[0];
        }
        if(empty($image)) {
            $width = !empty($width)?$width:400;
            $height = !empty($height)?$height:200;
            return "//via.placeholder.com/{$width}x{$height}";
        }
        $image = str_replace(MEDIA_NAME,'',$image);
        $sourceImage = MEDIA_PATH . $image;

        $sourceImage = str_replace('\\','/',$sourceImage);

        if(!file_exists($sourceImage)){
            $width = !empty($width)?$width:400;
            $height = !empty($height)?$height:200;
            return "//via.placeholder.com/{$width}x{$height}";
        }
        $CI =& get_instance();
        if($width != 0 && $height != 0){
            $size = sprintf('-%dx%d', $width, $height);
            $part = explode('.', $image);
            $ext = '.'.end($part);
            $newImage = str_replace($ext,$size.$ext, $image);
            $newPathImage = MEDIA_PATH.'thumb'.DIRECTORY_SEPARATOR.$newImage;
            $newPathImage = str_replace('\\','/',$newPathImage);
            if ( !file_exists( $newPathImage ) ) {
                if(!is_dir(dirname($newPathImage))){
                    mkdir(dirname($newPathImage), 0755, TRUE);
                }
                // CONFIGURE IMAGE LIBRARY
                $CI->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = $sourceImage;
                $config['new_image'] = $newPathImage;
                $config['maintain_ratio'] = TRUE;
                $config['create_thumb'] = FALSE;
                $config['height'] = $height;
                $config['width'] = $width;
                $imageSize = getimagesize($sourceImage);
                $imageWidth = intval($imageSize[0]);
                $imageHeight = intval($imageSize[1]);
                $dim = ($imageWidth / $imageHeight) - ($width / $height);
                $config['master_dim'] = ($dim > 0) ? "height" : "width";
                $CI->image_lib->initialize($config);
                if (!$CI->image_lib->resize()) {
                    print ($CI->image_lib->display_errors());
                } else {
                    if($crop == true){
                        $image_config['image_library'] = 'gd2';
                        $image_config['source_image'] = $newPathImage;
                        $image_config['new_image'] = $newPathImage;
                        $image_config['quality'] = $quality;
                        $image_config['maintain_ratio'] = FALSE;
                        $image_config['width'] = $width;
                        $image_config['height'] = $height;

                        $imageSize = getimagesize($newPathImage);
                        $imageWidth = intval($imageSize[0]);
                        $imageHeight = intval($imageSize[1]);
                        $cropStartX = ( $imageWidth / 2) - ( $width /2 );
                        $cropStartY = ( $imageHeight/ 2) - ( $height/2 );
                        $image_config['x_axis'] = $cropStartX;
                        $image_config['y_axis'] = $cropStartY;

                        $CI->image_lib->clear();
                        $CI->image_lib->initialize($image_config);
                        if (!$CI->image_lib->crop()) {
                            print ($CI->image_lib->display_errors());
                        }
                    }

                }
            }
            return str_replace('\\','/',MEDIA_URL.DIRECTORY_SEPARATOR.'thumb'.DIRECTORY_SEPARATOR.$newImage);
        }
        else {

            return str_replace('\\','/',MEDIA_URL.DIRECTORY_SEPARATOR.$image);
        }
    }
}
if ( ! function_exists('thePostThumbnail')) {
    function thePostThumbnail($image, $width = '', $height= '', $class='', $crop=true, $qlt='100%', $alt=''){
        $_this =& get_instance();
        $data = '<img class ="lazy '.$class.'" src="'.$_this->templates_assets.'images/dot.jpg" data-src="'.getImageThumb($image,$width,$height,$crop,$qlt).'" alt="'.$image.'"/>' ;
        echo $data;
    }
}

if ( ! function_exists('thePostThumbnailNone')) {
    function thePostThumbnailNone($image, $width = '', $height= '', $class='', $crop=true, $qlt='100%', $alt=''){
        $_this =& get_instance();
        $data = '<img style="display:none;" class ="showhover lazy '.$class.'" src="'.$_this->templates_assets.'images/dot.jpg" data-src="'.getImageThumb($image,$width,$height,$crop,$qlt).'" alt="'.$image.'"/>' ;
        echo $data;
    }
}



