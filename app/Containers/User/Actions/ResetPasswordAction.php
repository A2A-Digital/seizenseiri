<?php

namespace App\Containers\User\Actions;

use App\Ship\Exceptions\InternalErrorException;
use App\Ship\Parents\Actions\Action;
use App\Ship\Parents\Exceptions\Exception;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * Class ResetPasswordAction
 *
 * * @author  Sebastian Weckend
 */
class ResetPasswordAction extends Action
{

  /**
   * @param \App\Ship\Transporters\DataTransporter $data
   */
  public function run(DataTransporter $data)
  {

    $validate_token_expire = DB::table('password_resets')
      ->where('email', $data->email)
      ->where('created_at', '>', Carbon::now()->subMinute(60))->get();
    if (count($validate_token_expire) != 0) {
      $data = [
        'phone' => $data->phone,
        'token' => $data->token,
        'password' => $data->password,
        'password_confirmation' => $data->password,
      ];
      try {
        Password::broker()->reset(
          $data,
          function ($user, $password) {
            $user->forceFill([
              'password' => Hash::make($password),
              'remember_token' => Str::random(60),
            ])->save();
          }

        );
        return response('Success', 201);
      } catch (Exception $e) {
        throw new InternalErrorException();
      }
    } else {
      return response('Token expired', 401);
    }
  }
}