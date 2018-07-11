<?php

namespace LaraPress\Shortcodes;

use Illuminate\Support\ServiceProvider;

class ShortcodeServiceProvider extends ServiceProvider
{
    protected $shortcodeViewFolder = 'shortcodes';

    protected $shortcodes = [];

    protected $dynamicShortcodes = [];

    public function register()
    {
        actions()->listen('init', function () {
            foreach ($this->shortcodes as $shortcode) {
                $this->registerShortcodes($shortcode);
            }
            foreach ($this->dynamicShortcodes as $dynamicShortcode) {
                $this->registerDynamicShortcodes(app($dynamicShortcode));
            }
        });
    }

    protected function registerShortcodes($shortcode)
    {
        if (class_exists($shortcode)) {
            $shortcode = app($shortcode);
        }

        add_shortcode(
            $shortcode instanceof SimpleShortcode ? $shortcode->shortcode() : $shortcode,
            function ($attributes, $content = null) use ($shortcode) {
                if ($shortcode instanceof SimpleShortcode) {
                    return $shortcode->render($attributes, $content);
                }

                if ($this->hasRenderMethod($shortcode)) {
                    return $this->{$this->makeRenderMethodName($shortcode)}($attributes);
                }

                $view = $this->shortcodeViewFolder . '.' . $shortcode;

                if (view()->exists($view)) {
                    return view($view)->with([
                        'attributes' => $attributes,
                        'content'    => $content,
                    ]);
                }

                return '[' . $shortcode . ']';
            }
        );
    }

    protected function registerDynamicShortcodes(DynamicShortcode $dynamicShortcode)
    {
        foreach ($dynamicShortcode->shortcodes() as $shortcode) {
            add_shortcode(
                $shortcode->shortcode,
                function ($attributes, $content = '') use ($dynamicShortcode, $shortcode) {
                    return $dynamicShortcode->render(
                        $shortcode->attributes($attributes)->setContent($content)
                    );
                }
            );
        }
    }

    protected function hasRenderMethod($shortcode)
    {
        return method_exists($this, $this->makeRenderMethodName($shortcode));
    }

    protected function makeRenderMethodName($shortcode)
    {
        return camel_case($shortcode) . 'Shortcode';
    }
}
