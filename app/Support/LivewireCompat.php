<?php

namespace App\Support;

use Livewire\Component;

class LivewireCompat
{
    /**
     * Blade event call that works on Livewire 2 ($emit) and Livewire 3 ($dispatch).
     */
    public static function emit(string $event, array $params = []): string
    {
        $quote = function ($value) {
            return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], (string) $value) . "'";
        };

        if (method_exists(Component::class, 'dispatch')) {
            if ($params === []) {
                return '$dispatch(' . $quote($event) . ')';
            }

            $parts = [];
            foreach ($params as $key => $value) {
                $rendered = is_int($value) ? (string) $value : $quote($value);
                $parts[] = $key . ': ' . $rendered;
            }

            return '$dispatch(' . $quote($event) . ', { ' . implode(', ', $parts) . ' })';
        }

        $args = [$quote($event)];
        foreach ($params as $value) {
            $args[] = is_int($value) ? (string) $value : $quote($value);
        }

        return '$emit(' . implode(', ', $args) . ')';
    }
}
