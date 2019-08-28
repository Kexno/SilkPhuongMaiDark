<?php

'<?xml version="1.0" encoding="UTF-8"?>'
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo base_url(); ?></loc>
        <changefreq>always</changefreq>
        <priority>1.0</priority>
    </url>
    <?php if(!empty($categories)) foreach ($categories as $item):
        if($item->type === 'page')
            $url = getUrlAbout($item);
        else $url = getUrlCateNews($item);
        echo $url;
    ?>
        <url>
            <loc><?php echo $url; ?></loc>
            <lastmod><?php echo timeAgo($item->created_time,'Y-m-d') ?></lastmod>
            <changefreq>always</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; ?>
    <?php if(!empty($posts)) foreach ($posts as $item): $url = getUrlNews(['slug'=> $item->slug]); ?>
        <url>
            <loc><?php echo $url; ?></loc>
            <lastmod><?php echo timeAgo($item->created_time,'Y-m-d') ?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    <?php endforeach; ?>
    <?php /*if(!empty($products)) foreach ($products as $item): $url = getUrlProduct(['slug'=> $item->slug]); */?><!--
        <url>
            <loc><?php /*echo $url; */?></loc>
            <lastmod><?php /*echo timeAgo($item->created_time,'Y-m-d') */?></lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    --><?php /*endforeach; */?>
</urlset>
