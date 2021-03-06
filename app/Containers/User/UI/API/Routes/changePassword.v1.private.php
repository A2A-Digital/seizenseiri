<?php

/**
 * @apiGroup           Users
 * @apiName            registerUser
 * @api                {post} /v1/register Register User (create client)
 * @apiDescription     Register users as (client).
 *
 * @apiVersion         1.0.0
 * @apiPermission      none
 *
 * @apiParam           {String}  email (required)
 * @apiParam           {String}  password (required)
 * @apiParam           {String}  name (optional)
 * @apiParam           {String}  gender (optional)
 * @apiParam           {String}  birth (optional)
 *
 * @apiUse             UserSuccessSingleResponse
 */

$router->post('change_password', [
  'as' => 'api_user_change_password',
  'uses'  => 'Controller@changePassword',
  'middleware' =>[
    'auth:api',
  ]
]);
