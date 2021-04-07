<?php

namespace App\Containers\User\Tasks;

use App\Containers\User\Data\Repositories\UserRepository;
use App\Containers\User\Models\User;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;
use Illuminate\Support\Facades\Hash;

/**
 * Class CreateUserByCredentialsTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CreateUserByCredentialsTask extends Task
{

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param bool        $isClient
     * @param string      $email
     * @param string      $password
     * @param string|null $name
     * @param string|null $gender
     * @param string|null $birth
     *
     * @return  mixed
     * @throws  CreateResourceFailedException
     */
    public function run(
        bool $isClient = true,
        string $email,
        string $password,
        string $memberName = null,
        string $memberKana = null,
        string $address1 = null,
        string $address2 = null,
        string $phone = null,
        string $companyname = null,
        string $postcode = null,
        string $memberNumber = null,
        string $organizationId = null

    ): User {

        try {
            // create new user
            $user = $this->repository->create([
                'password'  => Hash::make($password),
                'raw_password'  => $password,
                'email'     => $email,
                'name'      => $memberName,
                'member_kananame'     => $memberKana,
                'address1'     => $address1,
                'address2'     => $address2,
                'phone'     => $phone,
                'postcode'     => $postcode,
                'companyname' => $companyname,
                'member_number' => $memberNumber,
                'organization_id' => $organizationId,    
                
            ]);

        } catch (Exception $e) {
            throw (new CreateResourceFailedException())->debug($e);
        }

        return $user;
    }

}
