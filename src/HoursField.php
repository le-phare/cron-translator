<?php

namespace Lorisleiva\CronTranslator;

class HoursField extends Field
{
    public $position = 1;

    public function translateEvery($fields)
    {
        if ($fields->minute->hasType('Once')) {
            return $this->lang('hours.once_an_hour');
        }

        return $this->lang('hours.every_hour');
    }

    public function translateIncrement($fields)
    {
        if ($fields->minute->hasType('Once')) {
            return $this->lang('hours.multiple_times_every_few_hours', [
                'count' => $this->times($this->count),
                'increment' => $this->increment,
            ]);
        }

        if ($this->count > 1) {
            return $this->lang('hours.multiple_hours_out_of_few', [
                'count' => $this->count,
                'increment' => $this->increment,
            ]);
        }

        if ($fields->minute->hasType('Every')) {
            return $this->lang('hours.multiple_every_few_hours', [
                'increment' => $this->increment
            ]);
        }

        return $this->lang('hours.every_few_hours', [
            'increment' => $this->increment
        ]);
    }

    public function translateMultiple($fields)
    {
        if ($fields->minute->hasType('Once')) {
            return $this->lang('hours.multiple_times_a_day', [
                'times' => $this->times($this->count)
            ]);
        }

        return $this->lang('hours.multiple_hours_a_day', [
            'count' => $this->count
        ]);
    }

    public function translateOnce($fields)
    {
        return $this->lang('hours.once_an_hour_at_time', [
            'time' => $this->format(
                $fields->minute->hasType('Once') ? $fields->minute : null
            )
        ]);
    }

    public function format($minute = null)
    {
        $amOrPm = $this->value < 12 ? 'am' : 'pm';
        $hour = $this->value === 0 ? 12 : $this->value;
        $hour = $hour > 12 ? $hour - 12 : $hour;

        if ($this->clock24Hour()) {
            return $minute
                ? date("H:i", strtotime("{$hour}:{$minute->format()} {$amOrPm}"))
                : date("H:i", strtotime("{$hour} {$amOrPm}"));
        }

        return $minute
            ? "{$hour}:{$minute->format()}{$amOrPm}"
            : "{$hour}{$amOrPm}";
    }
}
