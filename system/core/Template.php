<?php

namespace Core;

use Utilities\Str;

class Template
{

    /**
     * List of custom templates
     *
     * @var array
     */
    private static $templates = [];

    const RAW = '~';
    const NOT_RAW = '(?<!' . self::RAW . ')';
    const FORMAT = [
        'style'  => '/' . self::NOT_RAW . '\{%(\s?){1,}style( ?){1,}=( ?){1,}(.*)(\s?){1,}%\}/',
        'script' => '/' . self::NOT_RAW . '\{%(\s?){1,}script( ?){1,}=( ?){1,}(.*)(\s?){1,}%\}/',
        'icon'   => '/' . self::NOT_RAW . '\{%(\s?){1,}icon( ?){1,}=( ?){1,}(.*)(\s?){1,}%\}/',

        'comment'   => '/' . self::NOT_RAW . '\{#(?s).[^#\}]*#\}/',
        'plainecho' => '/' . self::NOT_RAW . '\{\!( ?){1,}(.*?)( ?){1,}\!\}/',
        'echo'      => '/' . self::NOT_RAW . '\{\{( ?){1,}(.*?)( ?){1,}\}\}/',
        'tag'       => '/' . self::NOT_RAW . '\{%( ?){1,}(.*?)( ?){1,}%\}/',
        'function'  => '/' . self::NOT_RAW . '{func}( ?){1,}\|([^\}!]{1,})/',
        'include'   => '/' . self::NOT_RAW . '@load\(( |\'?){1,}(.*)( |\'?){1,}\)/',

        'if'     => '/' . self::NOT_RAW . '\{(\s?){1,}(.*)\?(\s?){1,}\}/',
        'endif'  => '/' . self::NOT_RAW . '\{\?\}/',
        'else'   => '/' . self::NOT_RAW . '\{(\s?){1,}else(\s?){1,}\}/',
        'elseif' => '/' . self::NOT_RAW . '\{(\s?){1,}else(\s?){1,}(.*)(\s?){1,}\}/',

        'for'        => '/' . self::NOT_RAW . '\{( ?){1,}for( ){1,}(.*)( ){1,}in( ){1,}\((.*)( ?){1,},( ?){1,}(.*)( ?){1,}\)( ?){1,}\}/',
        'endfor'     => '/' . self::NOT_RAW . '\{( ?){1,}endfor( ?){1,}\}/'
    ];


    /**
     * Returns true if the template system is enabled, false otherwise
     * @return bool true if the template system is enabled, false otherwise
     */
    public static function isEnabled()
    {
        return CONFIG['template_on'];
    }


    /**
     * Applies the template format over a view and renders it.
     * The template format will be applied only if the template is enabled.
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data array present in the view
     * @param  bool  $cache  use or not the cache system
     */
    public static function render(string $dir, array $data, bool $cache)
    {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        $content = self::getContent($dir);

        if ($content === false) {
            return false;
        }

        if (self::isEnabled()) {
            $content = self::replaceAll($content);
        }

        //Cache system
        if ($cache && Cache::isEnabled()) {
            include(Cache::set($dir, $content));
        } else {
            $tmp_file = tmpfile();
            fwrite($tmp_file, $content);
            include(stream_get_meta_data($tmp_file)['uri']);
            fclose($tmp_file);
        }

    }


    /**
     * Returns the view content rendered or false in case of errors.
     * The template format will be applied only if the template is enabled.
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data array present in the view
     * @param  bool  $cache  use or not the cache system
     *
     * @return string the view content rendered or false in case of errors.
     */
    public static function getRender(string $dir, array $data, bool $cache)
    {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        $content = self::getContent($dir);

        if ($content === false) {
            return false;
        }

        if (self::isEnabled()) {
            $content = self::replaceAll($content);
        }

        ob_start();

        //Cache system
        if ($cache && Cache::isEnabled()) {
            include(Cache::set($dir, $content));
        } else {
            $tmp_file = tmpfile();
            fwrite($tmp_file, $content);
            include(stream_get_meta_data($tmp_file)['uri']);
            fclose($tmp_file);
        }

        $rendered_content = ob_get_contents();
        ob_end_clean();

        return $rendered_content;
    }


    /**
     * Returns the view content with the template format applied
     * or false if it doesn't exists
     *
     * @param  string  $dir  the view directory
     * @param  array  $data  the data array present in the view
     *
     * @return string|bool the view content with the template format applied
     * or false if it doesn't exists
     */
    public static function get(string $dir, array $data)
    {
        //Variables in data array
        if (is_array($data)) {
            extract($data);
            unset($data);
        }

        $content = self::getContent($dir);

        if ($content === false) {
            return false;
        }

        return self::replaceAll($content);
    }


    /**
     * Get the content of a view file
     *
     * @param  string  $dir  the view directory
     *
     * @return string|bool the view content or false if it doesn't exists
     */
    private static function getContent($dir)
    {
        $file_path = View::getPath($dir);

        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        } else {
            Log::error("View '$dir' doesn't exists");

            return false;
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
    private static function replaceCustom(string $content) {
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
    private static function replaceAll(string $content)
    {
        $content = self::replaceIncludes($content);
        $content = self::replaceImports($content);
        $content = self::replaceComments($content);
        $content = self::replaceFunctions($content);
        $content = self::replaceTags($content);
        $content = self::replaceConditionals($content);
        $content = self::replaceCustom($content);
        $content = self::replaceCycles($content);
        $content = self::replaceRaws($content);

        return $content;
    }


    /**
     * Apply the template includes
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the includes formatted
     */
    private static function replaceIncludes($content)
    {
        preg_match_all(self::FORMAT['include'], $content, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[1] as $key => $value) {
            $filename = Str::sanitizePath($matches[2][$key][0]);
            $content = str_replace($matches[0][$key][0], self::getContent($filename), $content);
        }

        return $content;
    }


    /**
     * Apply the template imports
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the import tags formatted
     */
    private static function replaceImports($content)
    {
        $content = preg_replace(self::FORMAT['style'], '<link rel="stylesheet" type="text/css" href=$4/>', $content);
        $content = preg_replace(self::FORMAT['script'], '<script type="text/javascript" src=$4></script>', $content);
        $content = preg_replace(self::FORMAT['icon'], '<link rel="icon" href=$4>', $content);

        return $content;
    }


    /**
     * Apply the template functions
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the functions formatted
     */
    private static function replaceFunctions($content)
    {
        $func = '{func}';

        //Escape
        $content = preg_replace(str_replace($func, 'e', self::FORMAT['function']), 'htmlspecialchars(strip_tags($2))',
            $content);
        //Uppercase
        $content = preg_replace(str_replace($func, 'upper', self::FORMAT['function']), 'strtoupper($2)', $content);
        //Lowercase
        $content = preg_replace(str_replace($func, 'lower', self::FORMAT['function']), 'strtolower($2)', $content);
        //First uppercase
        $content = preg_replace(str_replace($func, 'upperf', self::FORMAT['function']), 'ucfirst($2)', $content);
        //Length
        $content = preg_replace(str_replace($func, 'length', self::FORMAT['function']), 'strlen($2)', $content);
        //Count
        $content = preg_replace(str_replace($func, 'count', self::FORMAT['function']), 'count($2)', $content);
        //Title case
        $content = preg_replace(str_replace($func, 'title', self::FORMAT['function']), 'ucwords($2)', $content);
        //MD5
        $content = preg_replace(str_replace($func, 'md5', self::FORMAT['function']), 'md5($2)', $content);
        //Count words
        $content = preg_replace(str_replace($func, 'countwords', self::FORMAT['function']), 'str_word_count($2)', $content);
        //Trim
        $content = preg_replace(str_replace($func, 'trim', self::FORMAT['function']), 'trim($2)', $content);
        //nl2br
        $content = preg_replace(str_replace($func, 'nl2br', self::FORMAT['function']), 'nl2br($2)', $content);
        //Join
        $content = preg_replace(str_replace($func, 'join\((.*?)\)', self::FORMAT['function']), 'implode($1, $3)', $content);
        //Repeat
        $content = preg_replace(str_replace($func, 'repeat\((.*?)\)', self::FORMAT['function']), 'str_repeat($3, $1)', $content);

        return $content;
    }


    /**
     * Apply the template format over the tags of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the tags formatted
     */
    private static function replaceTags($content)
    {
        $content = preg_replace(self::FORMAT['echo'], '<?php echo htmlspecialchars($2, ENT_QUOTES) ?>', $content);
        $content = preg_replace(self::FORMAT['plainecho'], '<?php echo $2 ?>', $content);
        $content = preg_replace(self::FORMAT['tag'], '<?php $2 ?>', $content);

        return $content;
    }


    /**
     * Apply the template format over the conditionals of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the conditionals formatted
     */
    private static function replaceConditionals($content)
    {
        $content = preg_replace(self::FORMAT['endif'], '<?php endif; ?>', $content);
        $content = preg_replace(self::FORMAT['if'], '<?php if ($2): ?>', $content);
        $content = preg_replace(self::FORMAT['else'], '<?php else: ?>', $content);
        $content = preg_replace(self::FORMAT['elseif'], '<?php elseif ($3): ?>', $content);

        return $content;
    }


    /**
     * Apply the template format over the cycles of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the cycles formatted
     */
    private static function replaceCycles($content)
    {
        //For
        $content = preg_replace(self::FORMAT['for'], '<?php for (\$$3=$6; \$$3 <= $9; \$$3++): ?>', $content);
        $content = preg_replace(self::FORMAT['endfor'], '<?php endfor; ?>', $content);

        return $content;
    }


    /**
     * Remove the comments of a content
     *
     * @param  string  $content  the view content
     *
     * @return string the view content without the comments
     */
    private static function replaceComments($content)
    {
        return preg_replace(self::FORMAT['comment'], '', $content);
    }


    /**
     * Remove the raw tag from the rest of the tags
     *
     * @param  string  $content  the view content
     *
     * @return string the view content with the raw tag removed from the rest of the tags
     */
    private static function replaceRaws($content)
    {
        foreach (self::FORMAT as $format) {
            $format = trim($format, '/');
            $format = Str::remove($format, self::NOT_RAW);

            $content = preg_replace('/' . self::RAW . '(' . $format . ')/', '$1', $content);
        }

        return $content;
    }
}
