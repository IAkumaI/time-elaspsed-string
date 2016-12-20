<?php

/**
 * Время в строковом формате от текущей даты
 * @param $datetime
 * @param bool $full
 * @return string
 */
function time_elapsed_string($datetime, $full = false)
{
    if (!($datetime instanceof DateTime)) {
        $ago = DateTime::createFromFormat('Y-m-d H:i:s', $datetime);

        if (!$ago) {
            return $datetime;
        }
    } else {
        $ago = $datetime;
    }

    $now = new DateTime();
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => ['год', 'года', 'лет'],
        'm' => ['месяц', 'месяца', 'месяцев'],
        'w' => ['неделю', 'недели', 'недель'],
        'd' => ['день', 'дня', 'дней'],
        'h' => ['час', 'часа', 'часов'],
        'i' => ['минуту', 'минуты', 'минут'],
        's' => ['секунда', 'секунды', 'секунд'],
    );

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = decliner($diff->$k, $v, $diff->$k > 1);
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' назад' : 'только что';
}
