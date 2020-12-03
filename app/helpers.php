<?php

if (!function_exists('permissionToCaslRules')) {
    function permissionToCaslRules($rules)
    {
        $permissions = [];
        foreach ($rules as $rule) {
            $explodedRule = explode('.', $rule->name);
            $permissions = array_merge($permissions, [
                [
                    'subject' => $explodedRule[0],
                    'actions' => $explodedRule[1]
                ]
            ]);
        }
        return $permissions;
    }
}

function format_money($value, $currency = 'USD', $symbol = '$')
{
    return $currency.' '.$symbol.number_format($value, 2);
}

function email_link($email)
{
    return "<a href='mailto:$email'>$email</a>";
}

function mobile_link($mobile)
{
    return "<a href='tel:$mobile'>$mobile</a>";
}

function str_replace_limit($search, $replace, $string, $limit = 1)
{
    $pos = strpos($string, $search);

    if ($pos === false) {
        return $string;
    }

    $searchLen = strlen($search);

    for ($i = 0; $i < $limit; $i++) {
        $string = substr_replace($string, $replace, $pos, $searchLen);

        $pos = strpos($string, $search);

        if ($pos === false) {
            break;
        }
    }

    return $string;
}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if ($pos !== false) {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

function seconds_to_minutes($seconds)
{
    $min = intval($seconds / 60);
    return $min . ':' . str_pad(($seconds % 60), 2, '0', STR_PAD_LEFT);
}
