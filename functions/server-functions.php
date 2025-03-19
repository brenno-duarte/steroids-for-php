<?php declare(strict_types=1);

if (!function_exists('is_https')) {
    /**
     * Check to see if the current page is being served over SSL.
     *
     * @return bool
     */
    function is_https(): bool
    {
        return isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }
}

if (!function_exists('is_ajax')) {
    /**
     * Determine if current page request type is ajax.
     *
     * @return bool
     */
    function is_ajax(): bool
    {
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_current_url')) {
    /**
     * Return the current URL.
     *
     * @return string
     */
    function get_current_url(): string
    {
        $url = 'http://';

        if (is_https()) $url = 'https://';

        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $url .= $_SERVER['PHP_AUTH_USER'];
            if (isset($_SERVER['PHP_AUTH_PW'])) {
                $url .= ':' . $_SERVER['PHP_AUTH_PW'];
            }
            $url .= '@';
        }

        if (isset($_SERVER['HTTP_HOST'])) $url .= $_SERVER['HTTP_HOST'];
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80) $url .= ':' . $_SERVER['SERVER_PORT'];

        if (!isset($_SERVER['REQUEST_URI'])) {
            $url .= substr($_SERVER['PHP_SELF'], 1);

            if (isset($_SERVER['QUERY_STRING'])) $url .= '?' . $_SERVER['QUERY_STRING'];

            return $url;
        }

        $url .= $_SERVER['REQUEST_URI'];
        return $url;
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * Returns the IP address of the client.
     *
     * @param null|bool $header_containing_ip_address Default false
     *
     * @return string
     */
    function get_client_ip(?bool $header_containing_ip_address = null): string
    {
        if (!empty($header_containing_ip_address)) {
            return isset($_SERVER[$header_containing_ip_address])
                ? trim($_SERVER[$header_containing_ip_address])
                : false;
        }

        $knowIPkeys = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($knowIPkeys as $key) {
            if (array_key_exists($key, $_SERVER) !== true) {
                continue;
            }
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                
                if (filter_var(
                    $ip, 
                    FILTER_VALIDATE_IP, 
                    FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false
                ) {
                    return $ip;
                }
            }
        }

        return '';
    }
}
