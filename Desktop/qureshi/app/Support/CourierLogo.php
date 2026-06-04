<?php

namespace App\Support;

final class CourierLogo
{
    /**
     * Return the logo filename (stored under public/images/) for a given courier name.
     */
    public static function getLogoFilename(?string $courierName): string
    {
        $name = trim((string) $courierName);
        if ($name === '') {
            return 'logo.png';
        }

        // Normalize for case-insensitive matching
        $lower = mb_strtolower($name);

        return match ($lower) {
            'tcs' => 'Tcs logo.png',
            'trax' => 'Trax logo.png',
            'mp' => 'MP logo.png',
            'leopard' => 'Leopard logo.png',
            'leopards' => 'Leopard logo.png',
            'barqraftar', 'barq raftar' => 'Barqraftar logo.jfif',
            default => 'logo.png',
        };
    }
}

