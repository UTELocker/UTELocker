<?php

namespace App\Classes;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class Common
{
    public static function mappingRemovePrefix($inputs, $prefix): array
    {
        $result = [];
        foreach ($inputs as $key => $value) {
            $result[str_replace($prefix, '', $key)] = $value;
        }
        return $result;
    }

    public static function removeField($inputs, $field): array
    {
        if (array_key_exists($field, $inputs)) {
            unset($inputs[$field]);
        }
        return $inputs;
    }

    public static function assignField(
        $model,
        $field,
        array $inputs,
        $default = null,
        $options = []
    ): void {
        if (array_key_exists($field, $inputs)) {
            $model->setAttribute(
                $field,
                Arr::get($options, 'emptyToNull', true) ?
                    self::emptyToNull($inputs[$field])
                    : $inputs[$field]
            );
            if (Arr::get($options, 'emptyToZero', false) && empty($inputs[$field])) {
                $model->setAttribute($field, 0);
            }
        } else {
            $model->setAttribute($field, $default);
        }
    }

    public static function emptyToNull($value)
    {
        return $value === '' || $value === 'null' ? null : $value;
    }

    public static function parseDate($date = null, $format = 'Y-m-d')
    {
        if (!$date) {
            return null;
        }

        return Carbon::parse($date)->format($format);
    }

    public static function getYesNoOptions(): array
    {
        return [
            ['value' => CommonConstant::DATABASE_YES, 'text' => 'Yes'],
            ['value' => CommonConstant::DATABASE_NO, 'text' => 'No'],
        ];
    }
}
