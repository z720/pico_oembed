<?php

require dirname(__FILE__). '/../vendor/autoload.php';
require dirname(__FILE__). '/../Pico_oEmbed.php';

error_reporting(E_ALL);

class oEmbedPluginTest extends PHPUnit_Framework_TestCase {
    private $settings;

    public function __construct() {
        $this->settings['oEmbed_cache_dir'] = dirname(__FILE__) . '/cache';
        $this->settings['oEmbed_cache_life'] = 0;
    }

    public function testContentConversion() {
        $plugin = new Pico_oEmbed();
        $plugin->config_loaded($this->settings);

        $dh  = opendir(dirname(__FILE__) . '/fixtures');
				while (false !== ($filename = readdir($dh))) {
            $input = @file_get_contents(dirname(__FILE__) . '/fixtures/' . $filename);
            $target = @file_get_contents(dirname(__FILE__) . '/results/' . $filename);
            $output = $input;
            $plugin->after_parse_content($output);
            $this->assertEquals(trim($target), trim($output));
				}
    }

    public function testCache() {
        $plugin = new Pico_oEmbed();
        $plugin->config_loaded($this->settings);
        $content = @file_get_contents(dirname(__FILE__) . '/fixtures/flickr.html');
        $plugin->after_parse_content($content);
        $this->assertEquals(@file_get_contents(dirname(__FILE__) . '/results/flickr.html'), $content);
        $this->assertFileExists(dirname(__FILE__) . '/cache/oEmbed_48984f94ebd34bd3faed0b0666051f81');
    }
}

?>
