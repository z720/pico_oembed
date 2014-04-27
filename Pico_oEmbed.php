<?php

/**
 * oEmbed Pico Plugin
 * Will transform the oEmbedable URL in the content into the embedded representation from the provider.
 *
 * @author Sebastien Erard
 * @link http://z720.net/pico-oembed
 * @license http://opensource.org/licenses/MIT
 */
class Pico_oEmbed {

    private $cacheDir = './cache';
    private $cacheLife = 3600;

	public function plugins_loaded()
	{

	}

	public function config_loaded(&$settings)
	{
	    if(isset($settings['oEmbed_cache_dir'])) {
	        $this->cacheDir = $settings['oEmbed_cache_dir'];
	    }
	    if(isset($settings['oEmbed_cache_life'])) {
	        $this->cacheLife = $settings['oEmbed_cache_life'];
	    }
	}


	public function after_parse_content(&$content)
	{
	  $Cache = new FileCacheEngine($this->cacheDir, $this->cacheLife );
	  $Essence = Essence\Essence::instance( array(
        'Cache' => function () use ( $Cache ) { return $Cache; }
    ));
	  $content = $Essence->replace( $content );
	}

}

class FileCacheEngine implements Essence\Cache\Engine {
    private $dir, $lifetime;
    public function __construct( $dir, $life ) {
        $this->dir = $dir;
        $this->lifetime = $life;
        //echo sprintf("\nCache initialized in %s for %s", $dir, $life);
    }

    public function has($url) {
        return false;
        $cacheFile = $this->_cache_filename($url);
        if(!is_readable($cacheFile)) {
            return false;
            //throw new Exception("$url not in cache or cache not readable $cachefile");
        }
        if(time() - filemtime($cacheFile) > $this->lifetime) {
            // Cache expired
            unlink($cacheFile);
            return false;
            //throw new Exception("$url cache expired: delete $cacheFile");
        }
        return true;
    }

    public function get($url, $default = false) {
        if($this->has($url)) {
            $extracted = json_deocde(file_get_contents($this->_cache_filename($url)));
            return new Essence\Media($extracted);
            die(print_r($extracted));
        }
        return $default;
    }

    public function set($url, $data) {
	       $serialized = json_encode($data);
	       if(!$this->file_put_contents($this->_cache_filename($url), $serialized)) {
            throw new Exception("Not written: $url");
        }
        return $data;
    }

    private function _cache_filename($url) {
        $fn = $this->dir . '/oEmbed_' . /*md5*/($url);
        return $fn;
    }

    function file_put_contents($filename, $content) {
      //return file_put_contents($filename, $content) !== false;
        //print_r($content);
        $temp = tempnam($this->dir, 'temp');
        if (!($f = @fopen($temp, 'wb'))) {
            $temp = $this->dir . DIRECTORY_SEPARATOR . uniqid('temp');
            if (!($f = @fopen($temp, 'wb'))) {
                trigger_error("file_put_contents_atomic() : error writing temporary file '$temp'", E_USER_WARNING);
                return false;
            }
        }

        fwrite($f, $content);
        fclose($f);
        @chmod($temp, 0660);
        if (!@rename($temp, $filename)) {
            @unlink($filename);
            @rename($temp, $filename);
        }

        return true;
    }

}


?>
