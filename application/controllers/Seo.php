<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seo extends Public_Controller {
    protected $urls;
    protected $changefreqs;
    public function __construct() {
        parent::__construct();
        $this->urls = array();
        $this->changefreqs = array(
            'always',
            'hourly',
            'daily',
            'weekly',
            'monthly',
            'yearly',
            'never'
        );
    }
    public function sitemap(){
        $this->load->model(['category_model','post_model','product_model']);
        $categoryModel = new Category_model();
        $postModel = new Post_model();
        $productModel = new Product_model();
        $params = array(
            'lang_code' => $this->session->public_lang_code,
            'is_status' => 1,
            'limit' => 100
        );
        $categories = $categoryModel->getData($params);
        $posts = $postModel->getData($params);
        $products = $productModel->getData($params);

        $this->add(base_url(), '', 'always', 1.0);
        if(!empty($categories)) foreach ($categories as $item){
            $url = getUrlCateNews($item);
            $this->add($url, timeAgo($item->created_time,'Y-m-d'), 'weekly', 0.9);
        }

        if(!empty($posts)) foreach ($posts as $item) {
            $url = getUrlNews($item);
            $this->add($url, timeAgo($item->created_time,'Y-m-d'), 'weekly', 0.9);
        }

        if(!empty($products)) foreach ($products as $item) {
            $url = getUrlProduct($item);
            $this->add($url, timeAgo($item->created_time,'Y-m-d'), 'weekly', 0.9);
        }
        $this->output();
    }

    public function add($loc, $lastmod = NULL, $changefreq = NULL, $priority = NULL) {
        // Do not continue if the changefreq value is not a valid value
        if ($changefreq !== NULL && !in_array($changefreq, $this->changefreqs)) {
            show_error('Unknown value for changefreq: '.$changefreq);
            return false;
        }
        // Do not continue if the priority value is not a valid number between 0 and 1
        if ($priority !== NULL && ($priority < 0 || $priority > 1)) {
            show_error('Invalid value for priority: '.$priority);
            return false;
        }
        $item = new stdClass();
        $item->loc = $loc;
        $item->lastmod = $lastmod;
        $item->changefreq = $changefreq;
        $item->priority = $priority;
        $this->urls[] = $item;

        return true;
    }

    /**
     * Generate the sitemap file and replace any output with the valid XML of the sitemap
     *
     * @param string $type Type of sitemap to be generated. Use 'urlset' for a normal sitemap. Use 'sitemapindex' for a sitemap index file.
     * @access public
     * @return void
     */
    public function output($type = 'urlset') {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><'.$type.'/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        if ($type == 'urlset') {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('url');
                $child->addChild('loc', strtolower($url->loc));
                if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
                if (isset($url->changefreq)) $child->addChild('changefreq', $url->changefreq);
                if (isset($url->priority)) $child->addChild('priority', number_format($url->priority, 1));
            }
        } elseif ($type == 'sitemapindex') {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('sitemap');
                $child->addChild('loc', strtolower($url->loc));
                if (isset($url->lastmod)) $child->addChild('lastmod', $url->lastmod);
            }
        }
        $this->output->set_content_type('application/xml')->set_output($xml->asXml());
    }

    /**
     * Clear all items in the sitemap to be generated
     *
     * @access public
     * @return boolean
     */
    public function clear() {
        $this->urls = array();
        return true;
    }
}