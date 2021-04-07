<?php

namespace App\Containers\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\Models\User;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;

class CreateUserByCSVAction extends Action
{
  public function run(Request $request)
  {
    return $request->file($request->keys())->get();
  }

 
}