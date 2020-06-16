<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Helper;

/**
 * Class HtmlTag
 * @package Vemid\ProjectOne\Common\Helper
 */
class HtmlTag
{
    /**
     * @param array $links
     * @return string
     */
    public static function groupLink(array $links): string
    {
        return implode('&nbsp;&nbsp;', $links);
    }

    /**
     * @param $href
     * @param string $text
     * @param bool $class
     * @param bool $icon
     * @param bool $newWindow
     * @return string
     */
    public static function link($href, $text = '', $class = false,  $icon = false, $newWindow = false): string
    {
        return self::buildLink($href, $text, $class, $icon, $newWindow);
    }

    /**
     * @param $href
     * @param string $text
     * @param bool $class
     * @param bool $icon
     * @param bool $newWindow
     * @return string
     */
    private static function buildLink($href, $text = '', $class = false, $icon = false, $newWindow = false): string
    {
        $html = <<<HTML
            <a href="$href"
HTML;

        if ($class) {
            $html .= <<<HTML
 class="$class"
HTML;
        }

        if ($newWindow) {
            $html .= <<<HTML
 target="_blank"
HTML;
        }

        $html .= <<<HTML
>
HTML;
        if ($icon) {
            $html .= <<<HTML
<i class="fa fa-$icon"></i>
HTML;
        }

        $html .= <<<HTML
$text</a>
HTML;

        return trim(preg_replace('/\s\s+/', '', $html));
    }
}
