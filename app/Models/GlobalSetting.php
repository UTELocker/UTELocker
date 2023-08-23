<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
    use HasFactory;

    protected $appends = [
        'logo_url',
        'favicon_url',
        'login_background_url',
        'moment_date_format',
    ];

    const DATE_FORMATS = [
        'd-m-Y' => 'DD-MM-YYYY',
        'm-d-Y' => 'MM-DD-YYYY',
        'Y-m-d' => 'YYYY-MM-DD',
        'd.m.Y' => 'DD.MM.YYYY',
        'm.d.Y' => 'MM.DD.YYYY',
        'Y.m.d' => 'YYYY.MM.DD',
        'd/m/Y' => 'DD/MM/YYYY',
        'm/d/Y' => 'MM/DD/YYYY',
        'Y/m/d' => 'YYYY/MM/DD',
        'd/M/Y' => 'DD/MMM/YYYY',
        'd.M.Y' => 'DD.MMM.YYYY',
        'd-M-Y' => 'DD-MMM-YYYY',
        'd M Y' => 'DD MMM YYYY',
        'd F, Y' => 'DD MMMM, YYYY',
        'D/M/Y' => 'ddd/MMM/YYYY',
        'D.M.Y' => 'ddd.MMM.YYYY',
        'D-M-Y' => 'ddd-MMM-YYYY',
        'D M Y' => 'ddd MMM YYYY',
        'd D M Y' => 'DD ddd MMM YYYY',
        'D d M Y' => 'ddd DD MMM YYYY',
        'dS M Y' => 'Do MMM YYYY',
    ];

    const SELECT2_SHOW_COUNT = 20;

    public function getLogoUrlAttribute()
    {
        return '';
    }

    public static function getMonthsOfYear($full = 'F')
    {
        $months = [];

        for ($monthNumber = 1; $monthNumber <= Carbon::MONTHS_PER_YEAR; $monthNumber++) {
            $monthName = Carbon::create(null, $monthNumber)->translatedFormat($full);
            $months[] = $monthName;
        }

        return $months;
    }

    public static function getDaysOfWeek($full = 'D')
    {
        $days = [];

        for ($dayNumber = 0; $dayNumber < Carbon::DAYS_PER_WEEK; $dayNumber++) {
            $dayName = Carbon::now()->startOfWeek(0)->addDays($dayNumber)->translatedFormat($full);
            $days[] = $dayName;
        }

        return $days;
    }
}
