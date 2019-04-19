<?php

namespace Core;

class Template
{

    /**
     * Cache system.
     *
     * @var Core\Cache
     */
    private $cache;


    public function __construct($cache) {
        $this->cache = &$cache;
    }


    /**
     * Apply the template format over a content and render it
     * @param string $dir the view directory
     * @param array $data the data array present in the view
     * @param bool $cache use or not the cache system
     * @return string the view content
     */
    public function get(string $dir, array $data, bool $cache) {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        $content = $this->replaceAll($this->getContent($dir));

        //Cache system
        if ($cache && cacheEnabled()) {
            include($this->cache->get($dir, $content));
        } else {
            $temp = tmpfile();
            fwrite($temp, $content);
            include(stream_get_meta_data($temp)['uri']);
            fclose($temp);
        }

        return $content;
    }


    /**
     * Apply the template format over a content and return it
     * @param string $dir the view directory
     * @param array $data the data array present in the view
     * @return string the view content
     */
    public function getView(string $dir, array $data) {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        return $this->replaceAll($this->getContent($dir));
    }


    /**
     * Get the content of a view file
     * @param string $dir the view directory
     * @return string the view content
     */
    private function getContent($dir) {
        $file_path = getServerRoot() . WOLFF_APP_DIR . 'views/' . $dir;

        if (file_exists($file_path . '.php')) {
            return file_get_contents($file_path . '.php');
        } else {
            if (file_exists($file_path . '.html')) {
                return file_get_contents($file_path . '.html');
            } else {
                error_log("Error: View '" . $dir . "' doesn't exists");
                return null;
            }
        }
    }


    /**
     * Apply all the replace methods of the template
     * @param string $content the view content
     * @return string the view content with the template replaced
     */
    private function replaceAll(string $content) {
        $content = $this->replaceInclude($content);
        $content = $this->replaceFunctions($content);
        $content = $this->replaceTags($content);
        $content = $this->replaceConditionals($content);
        return $this->replaceCycles($content);
    }


    /**
     * Apply the template includes
     * @param string $content the view content
     * @return string the view content with the includes formated
     */
    private function replaceInclude($content) {
        preg_match_all('/@load\(( ?){1,}(.*)(.php|.html)( ?){1,}\)/', $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[1] as $key => $value) {
            $content = str_replace($matches[0][$key][0], $this->getContent($matches[2][$key][0]), $content);
        }

        return $content;
    }


    /**
     * Apply the template functions
     * @param string $content the view content
     * @return string the view content with the functions formated
     */
    private function replaceFunctions($content) {
        $format = '/{func}( ?){1,}\|([^\}]{1,})/';

        //Escape
        $content = preg_replace(str_replace('{func}', 'e', $format), 'htmlspecialchars(strip_tags($2))',
            $content);
        //HTMLspecialchars
        $content = preg_replace(str_replace('{func}', 'especial', $format), 'htmlspecialchars($2, ENT_QUOTES, \'UTF-8\')', $content);
        //Uppercase
        $content = preg_replace(str_replace('{func}', 'upper', $format), 'strtoupper($2)', $content);
        //Lowercase
        $content = preg_replace(str_replace('{func}', 'lower', $format), 'strtolower($2)', $content);
        //First uppercase
        $content = preg_replace(str_replace('{func}', 'upperf', $format), 'ucfirst($2)', $content);
        //Length
        $content = preg_replace(str_replace('{func}', 'length', $format), 'strlen($2)', $content);
        //Title case
        $content = preg_replace(str_replace('{func}', 'title', $format), 'ucwords($2)', $content);
        //MD5
        $content = preg_replace(str_replace('{func}', 'md5', $format), 'md5($2)', $content);
        //Count words
        $content = preg_replace(str_replace('{func}', 'countwords', $format), 'str_word_count($2)', $content);
        //Trim
        $content = preg_replace(str_replace('{func}', 'trim', $format), 'trim($2)', $content);
        //nl2br
        $content = preg_replace(str_replace('{func}', 'nl2br', $format), 'nl2br($2)', $content);
        //Join
        $content = preg_replace(str_replace('{func}', 'join\((.*?)\)', $format), 'implode($1, $3)', $content);
        //Repeat
        $content = preg_replace(str_replace('{func}', 'repeat\((.*?)\)', $format), 'str_repeat($3, $1)', $content);

        return $content;
    }


    /**
     * Apply the template format over the tags of a content
     * @param string $content the view content
     * @return string the view content with the tags formated
     */
    private function replaceTags($content) {
        $content = preg_replace('/\{\{(.*?)\}\}/', '<?php echo $1; ?>', $content);
        return preg_replace('/\{%(.*?)%\}/', '<?php $1 ?>', $content);
    }


    /**
     * Apply the template format over the conditionals of a content
     * @param string $content the view content
     * @return string the view content with the conditionals formated
     */
    private function replaceConditionals($content) {
        $content = preg_replace('/\{\?\}/', '<?php endif; ?>', $content);
        $content = preg_replace('/\{(\s?){1,}(.*)\?(\s?){1,}\}/', '<?php if ($2): ?>', $content);
        $content = preg_replace('/\{(\s?){1,}else(\s?){1,}\}/', '<?php else: ?>', $content);
        $content = preg_replace('/\{(\s?){1,}else(\s?){1,}(.*)(\s?){1,}\}/', '<?php elseif ($3): ?>', $content);
        return $content;
    }


    /**
     * Apply the template format over the cycles of a content
     * @param string $content the view content
     * @return string the view content with the cycles formated
     */
    private function replaceCycles($content) {
        //For
        $content = preg_replace('/\{( ?){1,}for( ){1,}(.*)( ){1,}in( ){1,}\((.*)( ?){1,},( ?){1,}(.*)( ?){1,}\)( ?){1,}\}/',
            '<?php for (\$$3=$6; \$$3 <= $9; \$$3++): ?>', $content);
        $content = preg_replace('/\{( ?){1,}for( ?){1,}\}/', '<?php endfor; ?>', $content);
        //Foreach
        $content = preg_replace('/\{( ?){1,}for( ?){1,}(.*)( ?){1,}as( ?){1,}(.*)( ?){1,}\}/',
            '<?php foreach ($3 as $6): ?>', $content);
        $content = preg_replace('/\{( ?){1,}foreach( ?){1,}\}/', '<?php endforeach; ?>', $content);
        return $content;
    }
}