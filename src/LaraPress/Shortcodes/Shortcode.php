<?php

namespace LaraPress\Shortcodes;

class Shortcode
{
    public $shortcode;
    public $attributes;
    public $content;

    public function __construct($shortcode, $attributes = [], $content = '')
    {
        $this->shortcode = $shortcode;
        $this->attributes = $attributes;
        $this->content = $content;
    }

    /**
     * @param $attributes
     * @return $this|Shortcode
     */
    public function attributes($attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * @param $content
     * @return $this|Shortcode
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}