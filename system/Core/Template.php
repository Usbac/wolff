<?php

namespace Core;

class Template
{

    /**
     * List of custom templates
     *
     * @var array
     */
    private static $templates = [];

    const COMMENT_FORMAT = '/\{#(?s).[^#\}]*#\}/';
    const PLAINECHO_FORMAT = '/\{\!(.*?)\!\}/';
    const ECHO_FORMAT = '/\{\{(.*?)\}\}/';
    const TAG_FORMAT = '/\{%(.*?)%\}/';
    const FUNCTION_FORMAT = '/{func}( ?){1,}\|([^\}!]{1,})/';
    const INCLUDE_FORMAT = '/@load\(( ?){1,}(.*)(.php|.html)( ?){1,}\)/';

    const IF_FORMAT = '/\{(\s?){1,}(.*)\?(\s?){1,}\}/';
    const ENDIF_FORMAT = '/\{\?\}/';
    const ELSE_FORMAT = '/\{(\s?){1,}else(\s?){1,}\}/';
    const ELSEIF_FORMAT = '/\{(\s?){1,}else(\s?){1,}(.*)(\s?){1,}\}/';

    const FOR_FORMAT = '/\{( ?){1,}for( ){1,}(.*)( ){1,}in( ){1,}\((.*)( ?){1,},( ?){1,}(.*)( ?){1,}\)( ?){1,}\}/';
    const ENDFOR_FORMAT = '/\{( ?){1,}for( ?){1,}\}/';
    const FOREACH_FORMAT = '/\{( ?){1,}for( ?){1,}(.*)( ?){1,}as( ?){1,}(.*)( ?){1,}\}/';
    const ENDFOREACH_FORMAT = '/\{( ?){1,}foreach( ?){1,}\}/';


    public function __construct()
    {
    }


    /**
     * Apply the template format over a content and render it
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data array present in the view
     * @param  bool  $cache  use or not the cache system
     *
     * @return string the view content
     */
    public function get(string $dir, array $data, bool $cache)
    {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        $content = $this->replaceAll($this->getContent($dir));

        //Cache system
        if ($cache && cacheEnabled()) {
            include(Cache::get($dir, $content));
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
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data array present in the view
     *
     * @return string the view content
     */
    public function getView(string $dir, array $data)
    {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        return $this->replaceAll($this->getContent($dir));
    }


    /**
     * Get the content of a view file
     *
     * @param  string  $dir  the view directory
     *
     * @return string the view content
     */
    private function getContent($dir)
    {
        $file_path = getServerRoot() . WOLFF_APP_DIR . 'views/' . $dir;

        if (file_exists($file_path . '.php')) {
            return file_get_contents($file_path . '.php');
        } elseif (file_exists($file_path . '.html')) {
            return file_get_contents($file_path . '.html');
        } else {
            error_log("Error: View '" . $dir . "' doesn't exists");

            return null;
        }
    }


    /**
     * Add a custom template
     *
     * @param  mixed  $function  the function with the custom template
     */
    public static function custom($function) {
        if (!is_callable($function)) {
            return;
        }
        
        self::$templates[] = $function;
    }


    /**
     * Apply the custom templates
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the custom templates formatted
     */
    private function replaceCustom(string $content) {
        if (empty(self::$templates)) {
            return $content;
        }

        foreach(self::$templates as $template) {
            $content = $template($content);
        }

        return $content;
    }


    /**
     * Apply all the replace methods of the template
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the template replaced
     */
    private function replaceAll(string $content)
    {
        $content = $this->replaceComments($content);
        $content = $this->replaceInclude($content);
        $content = $this->replaceFunctions($content);
        $content = $this->replaceTags($content);
        $content = $this->replaceConditionals($content);
        $content = $this->replaceCustom($content);

        return $this->replaceCycles($content);
    }


    /**
     * Apply the template includes
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the includes formatted
     */
    private function replaceInclude($content)
    {
        preg_match_all(self::INCLUDE_FORMAT, $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[1] as $key => $value) {
            $content = str_replace($matches[0][$key][0], $this->getContent($matches[2][$key][0]), $content);
        }

        return $content;
    }


    /**
     * Apply the template functions
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the functions formatted
     */
    private function replaceFunctions($content)
    {
        //Escape
        $content = preg_replace(str_replace('{func}', 'e', self::FUNCTION_FORMAT), 'htmlspecialchars(strip_tags($2))',
            $content);
        //Uppercase
        $content = preg_replace(str_replace('{func}', 'upper', self::FUNCTION_FORMAT), 'strtoupper($2)', $content);
        //Lowercase
        $content = preg_replace(str_replace('{func}', 'lower', self::FUNCTION_FORMAT), 'strtolower($2)', $content);
        //First uppercase
        $content = preg_replace(str_replace('{func}', 'upperf', self::FUNCTION_FORMAT), 'ucfirst($2)', $content);
        //Length
        $content = preg_replace(str_replace('{func}', 'length', self::FUNCTION_FORMAT), 'strlen($2)', $content);
        //Title case
        $content = preg_replace(str_replace('{func}', 'title', self::FUNCTION_FORMAT), 'ucwords($2)', $content);
        //MD5
        $content = preg_replace(str_replace('{func}', 'md5', self::FUNCTION_FORMAT), 'md5($2)', $content);
        //Count words
        $content = preg_replace(str_replace('{func}', 'countwords', self::FUNCTION_FORMAT), 'str_word_count($2)', $content);
        //Trim
        $content = preg_replace(str_replace('{func}', 'trim', self::FUNCTION_FORMAT), 'trim($2)', $content);
        //nl2br
        $content = preg_replace(str_replace('{func}', 'nl2br', self::FUNCTION_FORMAT), 'nl2br($2)', $content);
        //Join
        $content = preg_replace(str_replace('{func}', 'join\((.*?)\)', self::FUNCTION_FORMAT), 'implode($1, $3)', $content);
        //Repeat
        $content = preg_replace(str_replace('{func}', 'repeat\((.*?)\)', self::FUNCTION_FORMAT), 'str_repeat($3, $1)', $content);

        return $content;
    }


    /**
     * Apply the template format over the tags of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the tags formatted
     */
    private function replaceTags($content)
    {
        $content = preg_replace(self::ECHO_FORMAT, '<?php echo htmlspecialchars($1, ENT_QUOTES) ?>', $content);
        $content = preg_replace(self::PLAINECHO_FORMAT, '<?php echo $1 ?>', $content);

        return preg_replace(self::TAG_FORMAT, '<?php $1 ?>', $content);
    }


    /**
     * Apply the template format over the conditionals of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the conditionals formatted
     */
    private function replaceConditionals($content)
    {
        $content = preg_replace(self::ENDIF_FORMAT, '<?php endif; ?>', $content);
        $content = preg_replace(self::IF_FORMAT, '<?php if ($2): ?>', $content);
        $content = preg_replace(self::ELSE_FORMAT, '<?php else: ?>', $content);
        $content = preg_replace(self::ELSEIF_FORMAT, '<?php elseif ($3): ?>', $content);

        return $content;
    }


    /**
     * Apply the template format over the cycles of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the cycles formatted
     */
    private function replaceCycles($content)
    {
        //For
        $content = preg_replace(self::FOR_FORMAT, '<?php for (\$$3=$6; \$$3 <= $9; \$$3++): ?>', $content);
        $content = preg_replace(self::ENDFOR_FORMAT, '<?php endfor; ?>', $content);
        //Foreach
        $content = preg_replace(self::FOREACH_FORMAT, '<?php foreach ($3 as $6): ?>', $content);
        $content = preg_replace(self::ENDFOREACH_FORMAT, '<?php endforeach; ?>', $content);

        return $content;
    }


    /**
     * Remove the comments of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content without the comments
     */
    private function replaceComments($content)
    {
        return preg_replace(self::COMMENT_FORMAT, '', $content);
    }
}