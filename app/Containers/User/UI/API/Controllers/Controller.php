<?php

namespace App\Containers\User\UI\API\Controllers;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\Models\User;
use App\Containers\User\UI\API\Requests\ChangePasswordRequest;
use App\Containers\User\UI\API\Requests\CreateAdminRequest;
use App\Containers\User\UI\API\Requests\DeleteUserRequest;
use App\Containers\User\UI\API\Requests\FindUserByIdRequest;
use App\Containers\User\UI\API\Requests\ForgotPasswordRequest;
use App\Containers\User\UI\API\Requests\GetAllUsersRequest;
use App\Containers\User\UI\API\Requests\GetAuthenticatedUserRequest;
use App\Containers\User\UI\API\Requests\RegisterUserRequest;
use App\Containers\User\UI\API\Requests\ResetPasswordRequest;
use App\Containers\User\UI\API\Requests\LogoutRequest;
use App\Containers\User\UI\API\Requests\UpdateUserRequest;
use App\Containers\User\UI\API\Requests\ApplicationLessionRequest;
use App\Containers\User\UI\API\Requests\CreateUserByCSVRequest; 
use App\Containers\User\UI\API\Transformers\UserPrivateProfileTransformer;
use App\Containers\User\UI\API\Transformers\UserTransformer;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\Auth;
use App\Ship\Exceptions\CreateResourceFailedException;
use Illuminate\Support\Facades\Hash;



/**
 * Class Controller.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class Controller extends ApiController
{

    /**
     * @param \App\Containers\User\UI\API\Requests\RegisterUserRequest $request
     *
     * @return  mixed
     */
    public function registerUser(RegisterUserRequest $request)
    {
       $user_exit =   User::where('email',$request->email)
                        ->where('member_number',$request->member_number)
                        ->where('phone',$request->phone)
                        ->where('organization_id',$request->organization_id)
                        ->first();


      if($user_exit != []) {
        throw new CreateResourceFailedException();
      } 
      $roleId = $request->sanitizeInput([
        'role'
      ]);
      $user = Apiato::call('User@RegisterUserAction', [new DataTransporter($request)]);
      $user->assignRole($roleId);
      return $this->transform($user, UserTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\CreateAdminRequest $request
     *
     * @return  mixed
     */
    public function createAdmin(CreateAdminRequest $request)
    {
        $admin = Apiato::call('User@CreateAdminAction', [new DataTransporter($request)]);

        return $this->transform($admin, UserTransformer::class);
    }

    public function logout(LogoutRequest $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->noContent(202);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\UpdateUserRequest $request
     *
     * @return  mixed
     */
    public function updateUser(UpdateUserRequest $request)
    {
        $user = Apiato::call('User@UpdateUserAction', [new DataTransporter($request)]);

        return $this->transform($user, UserTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\DeleteUserRequest $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function deleteUser(DeleteUserRequest $request)
    {
        Apiato::call('User@DeleteUserAction', [new DataTransporter($request)]);

        return $this->noContent();
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\GetAllUsersRequest $request
     *
     * @return  mixed
     */
    public function getAllUsers(GetAllUsersRequest $request)
    {
        $users = Apiato::call('User@GetAllUsersAction');

        return $this->transform($users, UserTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\GetAllUsersRequest $request
     *
     * @return  mixed
     */
    public function getAllClients(GetAllUsersRequest $request)
    {
        $users = Apiato::call('User@GetAllClientsAction');

        return $this->transform($users, UserTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\GetAllUsersRequest $request
     *
     * @return  mixed
     */
    public function getAllAdmins(GetAllUsersRequest $request)
    {
        $users = Apiato::call('User@GetAllAdminsAction');

        return $this->transform($users, UserTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\FindUserByIdRequest $request
     *
     * @return  mixed
     */
    public function findUserById(FindUserByIdRequest $request)
    {
        $user = Apiato::call('User@FindUserByIdAction', [new DataTransporter($request)]);

        return $this->transform($user, UserTransformer::class);
    }

    /**
     * @param GetAuthenticatedUserRequest $request
     *
     * @return mixed
     */
    public function getAuthenticatedUser(GetAuthenticatedUserRequest $request)
    {
        $user = Apiato::call('User@GetAuthenticatedUserAction');
        return $this->transform($user, UserPrivateProfileTransformer::class);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\ResetPasswordRequest $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request)
    {

        return Apiato::call('User@ResetPasswordAction', [new DataTransporter($request)]);
    }

    /**
     * @param \App\Containers\User\UI\API\Requests\ForgotPasswordRequest $request
     *
     * @return  \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        Apiato::call('User@ForgotPasswordAction', [new DataTransporter($request)]);

        return $this->noContent(202);
    }
  public function changePassword(ChangePasswordRequest $request)
  {

    $currentPassword = Auth::User()->password;
    if( Hash::check($request['password'], $currentPassword) == false) {
      throw new NotFoundException();
    }
    $userId = Auth::User()->id;
    $user = User::find($userId);
    $user->password = Hash::make($request['new-password']);;
    $user->save();
    return  $this->transform($user, UserTransformer::class);
  }

  public function applicationSubmit(GetAuthenticatedUserRequest $request) {
    Apiato::call('User@SendApplicationMailAction', [new DataTransporter($request)]);
    return $this->noContent(202);
  }

  public function createUserbyCSV(CreateUserByCSVRequest $request) {
    $user_files =  $request->file($request->keys())->get();;
    $DATA_IMPORT = array_map("str_getcsv", explode("\n", $user_files));
    $COUNT = 0;
    set_time_limit(1000000000);
    foreach ($DATA_IMPORT as $_data_import) { 
     $COUNT = $COUNT + 1;

      if ($COUNT > 2) { 
        if(User::where('member_number','=',$_data_import[0])->first() == []) {
          $user = Apiato::call('User@CreateUserByCredentialsTask', [
            $isClient = true,
            $_data_import[14],
            $_data_import[2],
            $_data_import[3],
            $_data_import[4],
            $_data_import[6],
            $_data_import[7],
            $_data_import[1],
            '',
            $_data_import[5],
            $_data_import[0],
            1,
        ]);
        } else {
          User::where('member_number','=',$_data_import[0])->update(['name'=>$_data_import[3],'created_at'=>'2021-04-05 00:00:01','telephone2'=>$_data_import[10],'fax_num'=>$_data_import[12]]);
        }

      }
    }
    return $DATA_IMPORT;
  }
}
