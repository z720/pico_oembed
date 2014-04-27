<?php

require dirname(__FILE__). '/../vendor/autoload.php';
require dirname(__FILE__). '/../Pico_oEmbed.php';

error_reporting(E_ALL);

class CacheTest extends PHPUnit_Framework_TestCase {
    private $settings;

    public function __construct() {
        //$this->settings['oEmbed_cache_dir'] = dirname(__FILE__) . '/cache';
        //$this->settings['oEmbed_cache_life'] = 0;
    }

    public function testCacheWrite() {
        $cache = new FileCacheEngine(dirname(__FILE__) . '/cache', 1);
        $cache->set('test','Data');
    }

    public function testCache() {

    }
}

?>
