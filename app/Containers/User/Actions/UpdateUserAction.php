<?php

namespace App\Containers\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\Models\User;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\Hash;

/**
 * Class UpdateUserAction.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class UpdateUserAction extends Action
{

    /**
     * @param \App\Ship\Transporters\DataTransporter $data
     *
     * @return  \App\Containers\User\Models\User
     */
    public function run(DataTransporter $data): User
    {
        $userData = [
            'password'             => $data->password ? Hash::make($data->password) : null,
            'raw_password'         => $data->password ? $data->password : null ,
            'name'                 => $data->name,
            'email'                => $data->email,
            'email_two'            => $data->email_two,
            'member_kananame'      => $data->member_kananame,
            'address1'             => $data->address1,
            'address2'             => $data->address2,
            'phone'                => $data->phone,
            'telephone2'           => $data->telephone2,
            'companyname'          => $data->companyname,
            'postcode'             => $data->postcode,
            'line_id'              => $data->line_id,
            'facebook_id'          => $data->facebook_id,
            'fax_num'              => $data->fax_num,
            'birth'                => $data->birth,
           
        ];

        // remove null values and their keys
        $userData = array_filter($userData);

        $user = Apiato::call('User@UpdateUserTask', [$userData, $data->id]);

        return $user;
    }
}
