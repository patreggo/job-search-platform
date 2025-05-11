<?php

namespace App\Service;

use App\Entity\User;
use Exception;

class StringHelperService
{
    /** @var string */
    private const KEYSPACE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';


    /**
     * @param int $length
     * @return string
     * @throws Exception
     */
    public static function getRandomStr(int $length): string
    {
        $password = '';

        for ($i = 0; $i < $length; ++$i) {
            $password .= self::KEYSPACE[random_int(0, 61)];
        }

        return $password;
    }

    /**
     * @param string $string
     * @param bool $unique
     * @return string
     * @throws Exception
     */
    public static function generateSlugForEntity(string $string, bool $unique = true): string
    {
        return self::translitRuToEn($string) . ($unique === true ? '-' . strtolower(
                    self::getRandomStr(4)
                ) : '');
    }


    /**
     * @param string $text
     * @return string
     */
    public static function translitRuToEn(string $text): string
    {
        $converter = [
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sch',
            'ь' => '',
            'ы' => 'y',
            'ъ' => '',
            'э' => 'e',
            'ю' => 'yu',
            'я' => 'ya',
        ];

        $value = mb_strtolower($text);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
        $value = mb_ereg_replace('[-]+', '-', $value);

        return trim($value, '-');
    }

    /**
     * @param $format
     * @return array
     */
    public static function getStringArguments($format): array
    {
        preg_match_all(
            '/(?<=%)(?P<arguments>[a-zA-Z_]\w*)(?=\$s)/m',
            $format,
            $matches
        );

        return array_filter(array_map('array_filter', $matches), function ($match, $key) {
            return is_string($key);
        }, ARRAY_FILTER_USE_BOTH);
    }


    /**
     * @param string $string
     * @return string
     */
    public static function my_mb_ucfirst(string $string): string
    {
        return mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1);
    }
}
