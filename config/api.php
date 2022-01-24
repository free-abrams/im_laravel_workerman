<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Standards Tree
    |--------------------------------------------------------------------------
    |
    | Versioning an API with Dingo revolves around content negotiation and
    | custom MIME types. A custom type will belong to one of three
    | standards trees, the Vendor tree (vnd), the Personal tree
    | (prs), and the Unregistered tree (x).
    |
    | By default the Unregistered tree (x) is used, however, should you wish
    | to you can register your type with the IANA. For more details:
    | https://tools.ietf.org/html/rfc6838
    |
    */

    'standardsTree' => env('API_STANDARDS_TREE', 'x'),

    /*
    |--------------------------------------------------------------------------
    | API Subtype
    |--------------------------------------------------------------------------
    |
    | Your subtype will follow the standards tree you use when used in the
    | "Accept" header to negotiate the content type and version.
    |
    | For example: Accept: application/x.SUBTYPE.v1+json
    |
    */

    'subtype' => env('API_SUBTYPE', ''),

    /*
    |--------------------------------------------------------------------------
    | Default API Version
    |--------------------------------------------------------------------------
    |
    | This is the default version when strict mode is disabled and your API
    | is accessed via a web browser. It's also used as the default version
    | when generating your APIs documentation.
    |
    */

    'version' => env('API_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | Default API Prefix
    |--------------------------------------------------------------------------
    |
    | A default prefix to use for your API routes so you don't have to
    | specify it for each group.
    |
    */

    'prefix' => env('API_PREFIX', null),

    /*
    |--------------------------------------------------------------------------
    | Default API Domain
    |--------------------------------------------------------------------------
    |
    | A default domain to use for your API routes so you don't have to
    | specify it for each group.
    |
    */

    'domain' => env('API_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Name
    |--------------------------------------------------------------------------
    |
    | When documenting your API using the API Blueprint syntax you can
    | configure a default name to avoid having to manually specify
    | one when using the command.
    |
    */

    'name' => env('API_NAME', null),

    /*
    |--------------------------------------------------------------------------
    | Conditional Requests
    |--------------------------------------------------------------------------
    |
    | Globally enable conditional requests so that an ETag header is added to
    | any successful response. Subsequent requests will perform a check and
    | will return a 304 Not Modified. This can also be enabled or disabled
    | on certain groups or routes.
    |
    */

    'conditionalRequest' => env('API_CONDITIONAL_REQUEST', true),

    /*
    |--------------------------------------------------------------------------
    | Strict Mode
    |--------------------------------------------------------------------------
    |
    | Enabling strict mode will require clients to send a valid Accept header
    | with every request. This also voids the default API version, meaning
    | your API will not be browsable via a web browser.
    |
    */

    'strict' => env('API_STRICT', false),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Enabling debug mode will result in error responses caused by thrown
    | exceptions to have a "debug" key that will be populated with
    | more detailed information on the exception.
    |
    */

    'debug' => env('API_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Generic Error Format
    |--------------------------------------------------------------------------
    |
    | When some HTTP exceptions are not caught and dealt with the API will
    | generate a generic error response in the format provided. Any
    | keys that aren't replaced with corresponding values will be
    | removed from the final response.
    |
    */

    'errorFormat' => [
        'message' => ':message',
        'errors' => ':errors',
        'code' => ':code',
        'status_code' => ':status_code',
        'debug' => ':debug',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware that will be applied globally to all API requests.
    |
    */

    'middleware' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Providers
    |--------------------------------------------------------------------------
    |
    | The authentication providers that should be used when attempting to
    | authenticate an incoming API request.
    |
    */

    'auth' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Throttling / Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Consumers of your API can be limited to the amount of requests they can
    | make. You can create your own throttles or simply change the default
    | throttles.
    |
    */

    'throttling' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Response Transformer
    |--------------------------------------------------------------------------
    |
    | Responses can be transformed so that they are easier to format. By
    | default a Fractal transformer will be used to transform any
    | responses prior to formatting. You can easily replace
    | this with your own transformer.
    |
    */

    'transformer' => env('API_TRANSFORMER', Dingo\Api\Transformer\Adapter\Fractal::class),

    /*
    |--------------------------------------------------------------------------
    | Response Formats
    |--------------------------------------------------------------------------
    |
    | Responses can be returned in multiple formats by registering different
    | response formatters. You can also customize an existing response
    | formatter with a number of options to configure its output.
    |
    */

    'defaultFormat' => env('API_DEFAULT_FORMAT', 'json'),

    'formats' => [

        'json' => Dingo\Api\Http\Response\Format\Json::class,

    ],

    'formatsOptions' => [

        'json' => [
            'pretty_print' => env('API_JSON_FORMAT_PRETTY_PRINT_ENABLED', false),
            'indent_style' => env('API_JSON_FORMAT_INDENT_STYLE', 'space'),
            'indent_size' => env('API_JSON_FORMAT_INDENT_SIZE', 2),
        ],

    ],
    'privateKey' => env('API_PRIVATE_KEY', 'MIIEpAIBAAKCAQEAik+JFsFNTeXVv6oVbxHDIiCcHqKwpsBrejllp3t+icESyZ6eJH5Xc4uK7qjoiJOZ5Qy3ehpmuYc+OrROAQqOrsyUV3CX9mHIMJCzRV6lolhg6M1fnyKNfpHnhfMByPP+2lduC2nGrA+yW3wrsJh38ysYpzhbNnz6P4vTrhozzBfHwctgI4yfvL8jmIKefGl33KuLF9ssXA1jXcNm9GKyDp703Wo8jPF6tg/FVSb8c4wK1O3THJE7BQ4C/OsRXCrnOij6ludfQGWu6XkMc/J9NRLgKVY5GlZAFJAOo0lwE3TmJ2PQrHnhgukKRcDqDDCHiSI8xfuy2yjJ8rjcpSF34wIDAQABAoIBABvUgy4r+SUagRcO3z85IL2GOEPF0qvK/hVa5UR9CeooCmK9Yu7O6UPbqTn6jMemg4neNDECjPb56qCfVS7KdAliKtspUbqG1GRJSXlE4Sk4hU9yu7Hmnvf/3clLK2nHBtniS8dKImrOwcG8y4G+PCyW2GAVa+0b2rLcrNxUc2W2eutFd/r5PCP7u+9N18YALaIIu1JwkEkzubtiANFdTbBVqwe9LEDHXGPNr3fZcPEvL1oO9VCu2KMCBV9j3WJaBBGkkuwfFXmV0WzXmSd5+R/AlISNF8MFMErbDMKIlgY+ZreaDXfMCey7jFThGGGqzmj6Nw9JcLoMingEU8KVuUkCgYEA1YoPnxQQtcOlB6wWTBPvRu1V560yTM0s4Hb+rbsIbQbPUtQ0Ii6DTJ918rm6GsTaLdthz3DWJGYfQiLndaV8+VIXCvbToMOjjZZ8YbkgFxXHXK/nOKzkfZZ/nLJH/6IGI1X/TpvCmjRC+M8X+ZA10F/t8wUNlVTkkrV0M4jahscCgYEApdAPZm6GtJU5Z45aDX6q2O5vN8SVSnNyNSZo22c+I5u2VKXLCkbBAyS2c6DI+y3UE/eeeAonHaa0WflNp29UIsFn+6/NNHKPjxeaG1zmJlYVmSf75UQ2RaVtYgLqyvM4WNKUznBukAhxIKfYJZpgZkpUiE/qwbDzWa/KMBxOegUCgYEAuaAcWELPC9KYwQSdFWE23UcvKAfs88pfunh2h3tQpcYHr478CsK4LIFpvKgq2V5J8xcD/Zmu7VFA1vMUlbZX3zu3ADb0XIn0wP4R/bk55hduiGn469GPhoSvRf3MjLHB+DGnkCPilL3dggA0bSMpRIw/gsPfvPJhCA9ohevvWr0CgYEAopWZlCHKGeW8TOtLJ7JNSbq5+R/cFw2OLcmExaW9S2MSbHvI1EG+Xhuwfz5n88rjCdUiYKfr5OoiK5sFZqkGAbJNCIBAIS43z+IfrLbxwSNluDB5kTvKT44+6/zaRrgoRwfs+2NJNhfg/Vk22uA9p+84ZhSept+gLg/tnDEVThUCgYBQivvG+gS4/T4Vv4T8ekJnlryRQUZCWNG8KU037VKmHqZPi7OpmqwJVbTWWa+yBvJhve9pGxc7wIwSgEZ/FQPcXQ5TqRPJd+RHkdXSd9QH4FkprDJwb4lfLrPzDOfEm+BOOmw5HVr96aXg/PlyqmbS2L6I626cXswGziN7ZsLiAg=='),
    'publicKey' => env('API_PUBLIC_KEY','MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAik+JFsFNTeXVv6oVbxHDIiCcHqKwpsBrejllp3t+icESyZ6eJH5Xc4uK7qjoiJOZ5Qy3ehpmuYc+OrROAQqOrsyUV3CX9mHIMJCzRV6lolhg6M1fnyKNfpHnhfMByPP+2lduC2nGrA+yW3wrsJh38ysYpzhbNnz6P4vTrhozzBfHwctgI4yfvL8jmIKefGl33KuLF9ssXA1jXcNm9GKyDp703Wo8jPF6tg/FVSb8c4wK1O3THJE7BQ4C/OsRXCrnOij6ludfQGWu6XkMc/J9NRLgKVY5GlZAFJAOo0lwE3TmJ2PQrHnhgukKRcDqDDCHiSI8xfuy2yjJ8rjcpSF34wIDAQAB'),
];
