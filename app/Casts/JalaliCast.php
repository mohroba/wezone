<?php

namespace App\Casts;

use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class JalaliCast implements CastsAttributes
{
    /**
     * Set the value as Carbon date when saving to the database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return \Carbon\Carbon|string|null
     */
    public function set($model, $key, $value, $attributes)
    {
        if (empty($value)) {
            return null; // Handle null case explicitly
        }

        try {
            return Verta::parse($value)->toCarbon();
        } catch (\Exception $e) {
            // Return null or a default value when parsing fails
            return $value;
        }
    }

    /**
     * Get the value as a formatted Jalali date when retrieving from the database.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return string|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        if (empty($value)) {
            return null; // Handle null case explicitly
        }

        try {
            $v = Verta::parse($value);

            // Check if the time is exactly midnight (00:00:00)
            if ($v->hour == 0 && $v->minute == 0 && $v->second == 0) {
                // Return date format without time if the time is midnight
                return $v->setTimezone(new \DateTimeZone('Asia/Tehran'))->format('Y/m/d'); // e.g., 1400/02/12
            }

            // Otherwise, return full Jalali date and time
            return $v->setTimezone(new \DateTimeZone('Asia/Tehran'))->format('Y/m/d H:i:s'); // e.g., 1400/02/12 14:30:00
        } catch (\Exception $e) {
            // Return null or the original value in case of failure
            return $value;
        }
    }
}
