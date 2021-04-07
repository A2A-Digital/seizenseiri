<?php

namespace App\Containers\User\UI\API\Transformers;

use App\Containers\Authorization\UI\API\Transformers\RoleTransformer;
use App\Containers\User\Models\User;
use App\Ship\Parents\Transformers\Transformer;

/**
 * Class UserPrivateProfileTransformer.
 *
 * @author Johannes Schobel <johannes.schobel@googlemail.com>
 */
class UserTransformer extends Transformer
{

    /**
     * @var  array
     */
    protected $availableIncludes = [
        'roles',
    ];

    /**
     * @var  array
     */
    protected $defaultIncludes = [

    ];

    /**
     * @param \App\Containers\User\Models\User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
       
        $response = [
            'object'               => 'User',
            'id'                   => $user->getHashedKey(),
            'organization_id'      => $user->organization_id,
            'raw_password'         => $user->raw_password,
            'member_number'         => $user->member_number,
            'name'                 => $user->name,
            'email'                => $user->email,
            'email_two'            => $user->email_two,
            'member_kananame'      => $user->member_kananame,
            'address1'             => $user->address1,
            'address2'             => $user->address2,
            'fax_num'             => $user->fax_num,
            'phone'                => $user->phone,
            'postcode'             => $user->postcode,
            'telephone2'           => $user->telephone2,
            'birth'                => $user->birth,
            'companyname'          => $user->companyname,
            'line_id'              => $user->line_id,
            'facebook_id'          => $user->facebook_id,
            'created_at'           => $user->created_at,
            'updated_at'           => $user->updated_at,
            'readable_created_at'  => $user->created_at->diffForHumans(),
            'readable_updated_at'  => $user->updated_at->diffForHumans(),
        ];

        $response = $this->ifAdmin([
            'real_id'    => $user->id,
            'deleted_at' => $user->deleted_at,
        ], $response);

        return $response;
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }

}
