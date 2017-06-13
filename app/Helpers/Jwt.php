<?php

namespace App\Helpers;

/**
 * JSON Web Token implementation, based on this spec:
 * http://tools.ietf.org/html/draft-ietf-oauth-json-web-token-06.
 *
 * PHP version 5
 *
 * @category Authentication
 *
 * @author   Neuman Vong <neuman@twilio.com>
 * @author   Anant Narayanan <anant@php.net>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 *
 * @link     https://github.com/firebase/php-jwt
 */
class Jwt extends Helper
{
    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string      $jwt    The JWT
     * @param string|null $key    The secret key
     * @param bool        $verify Don't skip verification process
     *
     * @throws UnexpectedValueException Provided JWT was invalid
     * @throws DomainException          Algorithm was not provided
     *
     * @return object The JWT's payload as a PHP object
     *
     * @uses jsonDecode
     * @uses urlsafeB64Decode
     */
    public static function decode($jwt, $key = null, $verify = true)
    {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            //dd("you need access");
            throw new \UnexpectedValueException('You are currently logged out');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = self::jsonDecode(self::urlsafeB64Decode($headb64)))) {
            throw new \UnexpectedValueException('Invalid segment encoding');
        }
        if (null === $payload = self::jsonDecode(self::urlsafeB64Decode($bodyb64))) {
            throw new \UnexpectedValueException('Invalid segment encoding');
        }
        $sig = self::urlsafeB64Decode($cryptob64);
        if ($verify) {
            if (empty($header->alg)) {
                throw new \DomainException('Empty algorithm');
            }
            if ($sig != self::sign("$headb64.$bodyb64", $key, $header->alg)) {
                throw new \UnexpectedValueException('Signature verification failed');
            }
        }

        return $payload;
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param object|array $payload PHP object or array
     * @param string       $key     The secret key
     * @param string       $algo    The signing algorithm. Supported
     *                              algorithms are 'HS256', 'HS384' and 'HS512'
     *
     * @return string A signed JWT
     *
     * @uses jsonEncode
     * @uses urlsafeB64Encode
     */
    public static function encode($payload, $key, $algo = 'HS256')
    {
        $header = ['typ' => 'JWT', 'alg' => $algo];

        $segments = [];
        $segments[] = self::urlsafeB64Encode(self::jsonEncode($header));
        $segments[] = self::urlsafeB64Encode(self::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = self::sign($signing_input, $key, $algo);
        $segments[] = self::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    /**
     * Sign a string with a given key and algorithm.
     *
     * @param string $msg    The message to sign
     * @param string $key    The secret key
     * @param string $method The signing algorithm. Supported
     *                       algorithms are 'HS256', 'HS384' and 'HS512'
     *
     * @throws DomainException Unsupported algorithm was specified
     *
     * @return string An encrypted message
     */
    public static function sign($msg, $key, $method = 'HS256')
    {
        $methods = [
            'HS256' => 'sha256',
            'HS384' => 'sha384',
            'HS512' => 'sha512',
        ];
        if (empty($methods[$method])) {
            throw new \DomainException('Algorithm not supported');
        }

        return hash_hmac($methods[$method], $msg, $key, true);
    }

    /**
     * Decode a JSON string into a PHP object.
     *
     * @param string $input JSON string
     *
     * @throws DomainException Provided string was invalid JSON
     *
     * @return object Object representation of JSON string
     */
    public static function jsonDecode($input)
    {
        $obj = json_decode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            self::_handleJsonError($errno);
        } elseif ($obj === null && $input !== 'null') {
            throw new \DomainException('Null result with non-null input');
        }

        return $obj;
    }

    /**
     * Encode a PHP object into a JSON string.
     *
     * @param object|array $input A PHP object or array
     *
     * @throws DomainException Provided object could not be encoded to valid JSON
     *
     * @return string JSON representation of the PHP object or array
     */
    public static function jsonEncode($input)
    {
        $json = json_encode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            self::_handleJsonError($errno);
        } elseif ($json === 'null' && $input !== null) {
            throw new \DomainException('Null result with non-null input');
        }

        return $json;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }

        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function urlsafeB64Encode($input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * Helper method to create a JSON error.
     *
     * @param int $errno An error number from json_last_error()
     */
    private static function _handleJsonError($errno)
    {
        $messages = [
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
        ];
        throw new \DomainException(
            isset($messages[$errno])
            ? $messages[$errno]
            : 'Unknown JSON error: '.$errno
        );
    }
}
