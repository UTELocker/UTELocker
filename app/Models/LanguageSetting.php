<?php

namespace App\Models;

use App\Classes\CommonConstant;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\LanguageSetting
 *
 * @property int $id
 * @property string $language_code
 * @property string $language_name
 * @property string $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $icon
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereLanguageCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereLanguageName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereUpdatedAt($value)
 * @property string|null $flag_code
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageSetting whereFlagCode($value)
 */

class LanguageSetting extends Model
{
    const LANGUAGES_TRANS = [
        'en' => 'English',
        'vi' => 'Tiếng Việt',
    ];

    const LANGUAGES = [
        [
            'language_code' => 'en',
            'flag_code' => 'en',
            'language_name' => 'English',
            'enabled' => CommonConstant::DATABASE_YES
        ],
        [
            'language_code' => 'vi',
            'flag_code' => 'vi',
            'language_name' => 'Tiếng Việt',
            'enabled' => CommonConstant::DATABASE_NO
        ],
    ];

    public static function getEnabledLanguages()
    {
        return self::where('enabled', CommonConstant::DATABASE_YES)->get();
    }
}
