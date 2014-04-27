<?php

require dirname(__FILE__). '/../vendor/autoload.php';
require dirname(__FILE__). '/../oEmbedManager.php';

class oEmbedTest extends PHPUnit_Framework_TestCase {
    private $dir;
    private $fixtures;
    
    public function __construct() {
        $this->dir =  dirname(__FILE__) . '/cache';
        @mkdir($this->dir,0660, true);
        $this->fixtures = array(
            "http://www.flickr.com/photos/bees/2341623661/" => '<img src="http://farm4.staticflickr.com/3123/2341623661_7c99f48bbf_n.jpg" alt="ZB8T0193" width="320" height="213" />',
            "https://twitter.com/jack/status/20" => '<blockquote class="twitter-tweet" width="500"><p>just setting up my twttr</p>&mdash; Jack Dorsey (@jack) <a href="https://twitter.com/jack/statuses/20">March 21, 2006</a></blockquote>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>'
        );
    }
    
    public function testDirectData() {
        $oEmbed = new oEmbedManager($this->dir, 0);
        foreach($this->fixtures as $url => $content) {
            $this->assertEquals($content, $oEmbed->parse($url));
        }
    }
    
    public function testCacheExpiration() {
        return;
    }
    
    public function testCache() {
        return;
    }
}
?>