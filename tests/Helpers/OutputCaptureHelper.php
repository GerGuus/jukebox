<?php

declare(strict_types=1);

namespace Tests\Helpers;

class OutputCaptureHelper
{
    public static function captureOutput(callable $callback): string
    {
        ob_start();

        try {
            $callback();
            return (string) ob_get_contents();
        } finally {
            ob_end_clean();
        }
    }
}