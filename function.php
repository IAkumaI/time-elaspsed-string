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

/**
 * Склонение строки
 *
 * decliner(10, ['товар', 'товара', 'товаров']);
 * @param $cnt
 * @param $words
 * @param $implode
 * @return string
 */
function decliner($cnt, $words, $implode)
{
    $cnt__ = $cnt;
    $cnt = abs($cnt) % 100;
    $n1 = $cnt % 10;

    if ($cnt > 10 && $cnt < 20)
        return $implode === true ? $cnt__ . ' ' . $words[2] : $words[2];
    else if ($n1 > 1 && $n1 < 5)
        return $implode === true ? $cnt__ . ' ' . $words[1] : $words[1];
    else if ($n1 == 1)
        return $implode === true ? $cnt__ . ' ' . $words[0] : $words[0];
    return $implode === true ? $cnt__ . ' ' . $words[2] : $words[2];
}
