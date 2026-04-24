<?php

function setting($key, $multiple = false)
{
    try {
        $appSettings = $GLOBALS['app_settings'] ?? [];

        // normalize collection to array
        if ($appSettings instanceof \Illuminate\Support\Collection) {
            $appSettings = $appSettings->toArray();
        }

        if (!$multiple) {
            foreach ($appSettings as $item) {
                if (!isset($item['title'])) {
                    continue;
                }
                if ($item['title'] == $key) {
                    // prefer direct 'value' if present
                    if (isset($item['value'])) {
                        return $item['value'];
                    }

                    // otherwise, check nested setting_values and return first value
                    if (!empty($item['setting_values']) && is_array($item['setting_values'])) {
                        $first = $item['setting_values'][0];
                        return $first['value'] ?? '';
                    }

                    return '';
                }
            }

            return '';
        } else {
            $matches = [];
            foreach ($appSettings as $item) {
                if (isset($item['title']) && $item['title'] == $key) {
                    $matches[] = $item;
                }
            }
            return $matches;
        }
    } catch (\Throwable $th) {
        // swallow errors and return empty string or array depending on $multiple
        return $multiple ? [] : '';
    }
}
