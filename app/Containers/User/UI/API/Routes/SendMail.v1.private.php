<?php

/**
 * @apiGroup           User
 * @apiName            applicationSubmit
 *
 * @api                {POST} /v1/user/sendmail Endpoint title here..
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
$router->post('user/sendmail', [
    'as' => 'api_user_application_submit',
    'uses'  => 'Controller@applicationSubmit',
    'middleware' => [
      'auth:api',
    ],
]);
