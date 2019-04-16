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
        $file_path = getServerRoot() . WOLFF_APP_DIR . 'views' . DIRECTORY_SEPARATOR . $dir;

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
        $format = '/\{\{( ?){1,}{func}( ?){1,}\|([^\}]{1,})/';

        //Escape
        $content = preg_replace(str_replace('{func}', 'e', $format), '<?php echo htmlspecialchars(strip_tags($3)); ',
            $content);
        //HTMLspecialchars
        $content = preg_replace(str_replace('{func}', 'especial', $format),
            '<?php echo htmlspecialchars($3, ENT_QUOTES, \'UTF-8\'); ', $content);
        //Uppercase
        $content = preg_replace(str_replace('{func}', 'upper', $format), '<?php echo strtoupper($3); ', $content);
        //Lowercase
        $content = preg_replace(str_replace('{func}', 'lower', $format), '<?php echo strtolower($3); ', $content);
        //First uppercase
        $content = preg_replace(str_replace('{func}', 'upperf', $format), '<?php echo ucfirst($3); ', $content);
        //Length
        $content = preg_replace(str_replace('{func}', 'length', $format), '<?php echo strlen($3); ', $content);
        //Title case
        $content = preg_replace(str_replace('{func}', 'title', $format), '<?php echo ucwords($3); ', $content);
        //MD5
        $content = preg_replace(str_replace('{func}', 'md5', $format), '<?php echo md5($3); ', $content);
        //Count words
        $content = preg_replace(str_replace('{func}', 'countwords', $format), '<?php echo str_word_count($3); ',
            $content);
        //Trim
        $content = preg_replace(str_replace('{func}', 'trim', $format), '<?php echo trim($3); ', $content);
        //nl2br
        $content = preg_replace(str_replace('{func}', 'nl2br', $format), '<?php echo nl2br($3); ', $content);
        //Join
        $content = preg_replace(str_replace('{func}', 'join\((.*?)\)', $format), '<?php echo implode($2, $4); ',
            $content);
        //Repeat
        $content = preg_replace(str_replace('{func}', 'repeat\((.*?)\)', $format), '<?php echo str_repeat($4, $2); ',
            $content);

        return $content;
    }


    /**
     * Apply the template format over the tags of a content
     * @param string $content the view content
     * @return string the view content with the tags formated
     */
    private function replaceTags($content) {
        $search = array('{{', '}}', '{%', '%}');
        $replace = array('<?php echo ', '?>', '<?php ', ' ?>');
        return str_replace($search, $replace, $content);
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