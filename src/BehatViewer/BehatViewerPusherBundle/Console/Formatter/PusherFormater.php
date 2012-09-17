<?php
namespace BehatViewer\BehatViewerPusherBundle\Console\Formatter;

use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyleInterface;

class PusherFormater implements OutputFormatterInterface
{
    const START_PATTERN = "/\\033\[(\d+)(?:|;(\d+))(?:|;(\d+))m(((?!\033).)*)\\033\[\d+m/is";

    private static $availableForegroundColors = array(
        'black'     => 30,
        'red'       => 31,
        'green'     => 32,
        'yellow'    => 33,
        'blue'      => 34,
        'magenta'   => 35,
        'cyan'      => 36,
        'white'     => 37
    );
    private static $availableBackgroundColors = array(
        'black'     => 40,
        'red'       => 41,
        'green'     => 42,
        'yellow'    => 43,
        'blue'      => 44,
        'magenta'   => 45,
        'cyan'      => 46,
        'white'     => 47
    );
    private static $availableOptions = array(
        'bold'          => 1,
        'underscore'    => 4,
        'blink'         => 5,
        'reverse'       => 7
    );

    public function format($message)
    {
        $formatter = new \Symfony\Component\Console\Formatter\OutputFormatter(true);
        $message = $formatter->format($message);
        $message = htmlentities($message);

        $message = preg_replace_callback(self::START_PATTERN, array($this, 'replaceStyle'), $message);

        return trim($message, "\r\n") . PHP_EOL;
    }

    private function replaceStyle($match)
    {
        $foreground = null;
        if (isset($match[1])) {
            if (false !== ($key = array_search($match[1], self::$availableForegroundColors))) {
                $foreground = $key;
            }
        }

        $background = null;
        if (isset($match[2])) {
            if (false !== ($key = array_search($match[2], self::$availableBackgroundColors))) {
                $background = $key;
            }
        }

        $opt = null;
        if (isset($match[3])) {
            if (false !== ($key = array_search($match[3], self::$availableOptions))) {
                $opt = $key;
            }
        }

        $code = 'style="';
        if (null !== $foreground) {
            $code .= 'color: ' . $foreground . ';';
        }
        if (null !== $background) {
            $code .= 'background-color: ' . $background . ';';
        }
        if (null !== $opt) {
            switch ($opt) {
                case 'bold':
                    $code .= 'font-weight: bold;';
                    break;
                case 'underscore':
                    $code .= 'text-decoration: underline;';
                    break;
                case 'blink':
                    $code .= 'text-decoration: blink;';
                    break;
                case 'reverse':
                    $code .= 'direction: rtl; unicode-bidi: bidi-override;';
                    break;
            }
        }
        $code .= '"';

        return sprintf('<span %s>%s</span>', $code, $match[4]);
    }

    public function setDecorated($decorated)
    {
        return $this;
    }

    public function isDecorated()
    {
        return true;
    }

    public function setStyle($name, OutputFormatterStyleInterface $style)
    {
        return $this;
    }

    public function hasStyle($name)
    {
        return true;
    }

    public function getStyle($name)
    {
        return null;
    }
}
