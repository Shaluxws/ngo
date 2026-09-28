<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class FileSecurityService
{
    /**
     * Dangerous patterns that must never exist inside uploaded SVG files.
     */
    protected static array $dangerousSvgPatterns = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/onload\s*=/i',
        '/onerror\s*=/i',
        '/onclick\s*=/i',
        '/onmouseover\s*=/i',
        '/onfocus\s*=/i',
        '/javascript\s*:/i',
        '/vbscript\s*:/i',
        '/<foreignObject\b[^>]*>(.*?)<\/foreignObject>/is',
        '/<iframe\b[^>]*>(.*?)<\/iframe>/is',
    ];

    /**
     * Validate and sanitize an uploaded file.
     * Throws ValidationException if unsafe content is detected.
     */
    public static function validateAndSanitize(UploadedFile $file, string $attributeName = 'file'): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = strtolower($file->getMimeType() ?: '');

        // Check for double extension attacks (e.g., evil.php.png)
        $originalName = $file->getClientOriginalName();
        if (preg_match('/\.(php|phtml|php3|php4|php5|php7|pht|phar|exe|sh|bat|cmd|js|html|htm)\./i', $originalName)) {
            throw ValidationException::withMessages([
                $attributeName => ['The file contains an invalid or potentially unsafe filename format.'],
            ]);
        }

        // SVG security inspection
        if ($extension === 'svg' || str_contains($mime, 'svg') || str_contains($mime, 'xml')) {
            self::validateSvg($file, $attributeName);
        }
    }

    /**
     * Inspect SVG content for malicious vectors.
     */
    protected static function validateSvg(UploadedFile $file, string $attributeName): void
    {
        $content = file_get_contents($file->getRealPath());
        if ($content === false) {
            throw ValidationException::withMessages([
                $attributeName => ['Unable to read uploaded file.'],
            ]);
        }

        foreach (self::$dangerousSvgPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw ValidationException::withMessages([
                    $attributeName => ['The uploaded SVG contains unsafe or executable script content.'],
                ]);
            }
        }
    }
}
