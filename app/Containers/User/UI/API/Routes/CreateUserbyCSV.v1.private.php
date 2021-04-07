<?php

/**
 * @apiGroup           User
 * @apiName            createUserbyCSV
 *
 * @api                {POST} /v1/register/csv Endpoint title here..
 * @apiDescription     Endpoint description here..
 *
 * @apiVersion         1.0.0
 * @apiPermission      none
 *
 * @apiParam           {String}  parameters here..
 *
 * @apiSuccessExample  {json}  Success-Response:
 * HTTP/1.1 200 OK
{
  // Insert the response of the request here...
}
 */

/** @var Route $router */
$router->post('register/csv', [
    'as' => 'api_user_create_userby_c_s_v',
    'uses'  => 'Controller@createUserbyCSV',
    'middleware' => [
        'auth:api',
    ],
]);
