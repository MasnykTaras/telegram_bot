<?php
/**
 * author: atx
 * Date: 09.04.19
 * Time: 0:57
 */

namespace common\components\helpers;


class IpHelper
{
    public static function ipInList($ip, $list)
    {
        foreach ($list as $item) {
            if (strstr($item, "/")) {
                if (self::ipInSubnet($ip, $item))
                    return true;
            } elseif (strstr($item, "-")) {
                if (self::ipInRange($ip, $item))
                    return true;
            } else {
                if (self::ipIsEqual($ip, $item))
                    return true;
            }
        }

        return false;
    }

    public static function ipInSubnet($ip, $subnet)
    {
        $parts = explode('/', $subnet);
        $net = isset($parts[0]) ? $parts[0] : null;
        $mask = isset($parts[1]) ? $parts[1] : null;

        if (!$mask)
            return false;

        return (ip2long($ip) & (-1 << (32 - $mask))) == ip2long($net);
    }

    public static function ipInRange($ip, $range)
    {
        $parts = explode('-', $range);
        $startIp = isset($parts[0]) ? trim($parts[0]) : null;
        $endIp = isset($parts[1]) ? trim($parts[1]) : null;

        if (!$startIp || !$endIp)
            return false;

        return (ip2long($ip) >= ip2long($startIp) && ip2long($ip) <= ip2long($endIp));
    }

    public static function ipIsEqual($ip, $secondIp)
    {
        return ip2long($ip) == ip2long($secondIp);
    }
}