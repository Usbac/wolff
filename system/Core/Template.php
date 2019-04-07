<?php

namespace Core;

class Template {

    private $cache;


    public function __construct($cache) {
        $this->cache = &$cache;
    }
    

    /**
     * Apply the template format over a content and render it
     * @param string $dir the view directory
     * @param array $data the data array present in the view
     * @param bool $cache use or not the cache system
     * @param bool $returnView if true the view won't be included, only returned
     * @return string the view content
     */
    public function get(string $dir, array $data, bool $cache, bool $returnView) {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . APP . 'view' . DIRECTORY_SEPARATOR . $dir;

        if (file_exists($file_path . '.php')) {
            $content = file_get_contents($file_path . '.php');
        } else if (file_exists($file_path . '.html')) {
            $content = file_get_contents($file_path . '.html');
        } else {
            error_log("Error: View '" . $dir . "' doesn't exists");
            return null;
        }

        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }
        
        $content = $this->replaceFunctions($content);
        $content = $this->replaceTags($content);
        $content = $this->replaceConditionals($content);
        $content = $this->replaceCycles($content);

        if ($returnView) {
            return $content;
        }
        
        //Cache system
        if ($cache) {
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
     * Apply the template functions
     * @param string $content the view content
     * @return string the view content with the functions formated
     */
    private function replaceFunctions($content) {
        $format = '/\{\{( ?){1,}{func}( ?){1,}\|([^\}]{1,})/';

        //Escape
        $content = preg_replace(str_replace('{func}', 'e', $format), '<?php echo htmlspecialchars(strip_tags($3)); ', $content);
        //HTMLspecialchars
        $content = preg_replace(str_replace('{func}', 'especial', $format), '<?php echo htmlspecialchars($3, ENT_QUOTES, \'UTF-8\'); ', $content);
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
        $content = preg_replace(str_replace('{func}', 'countwords', $format), '<?php echo str_word_count($3); ', $content);
        //Trim
        $content = preg_replace(str_replace('{func}', 'trim', $format), '<?php echo trim($3); ', $content);
        //nl2br
        $content = preg_replace(str_replace('{func}', 'nl2br', $format), '<?php echo nl2br($3); ', $content);
        //Join
        $content = preg_replace(str_replace('{func}', 'join\((.*?)\)', $format), '<?php echo implode($2, $4); ', $content);
        //Repeat
        $content = preg_replace(str_replace('{func}', 'repeat\((.*?)\)', $format), '<?php echo str_repeat($4, $2); ', $content);

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
        $content = preg_replace('/\{( ?){1,}for( ){1,}(.*)( ){1,}in( ){1,}\((.*)( ?){1,},( ?){1,}(.*)( ?){1,}\)( ?){1,}\}/', '<?php for (\$$3=$6; \$$3 <= $9; \$$3++): ?>', $content);
        $content = preg_replace('/\{( ?){1,}for( ?){1,}\}/', '<?php endfor; ?>', $content);
        //Foreach
        $content = preg_replace('/\{( ?){1,}for( ?){1,}(.*)( ?){1,}as( ?){1,}(.*)( ?){1,}\}/', '<?php foreach ($3 as $6): ?>', $content);
        $content = preg_replace('/\{( ?){1,}foreach( ?){1,}\}/', '<?php endforeach; ?>', $content);
        return $content;
    }
}