<?php

namespace App\Containers\User\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\User\Mails\UserForgotPasswordMail;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Actions\Action;
use App\Ship\Transporters\DataTransporter;
use Illuminate\Support\Facades\Mail;
use App\Containers\User\Mails\UserApplicationMail;
use Illuminate\Support\Facades\Auth;

/**
 * Class ForgotPasswordAction
 *
 * @author  Sebastian Weckend
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class SendApplicationMailAction extends Action
{

    /**
     * @param \App\Ship\Transporters\DataTransporter $data
     */
    public function run(DataTransporter $data): void
    {

        // send email
        $user = Auth::user();
        Mail::send(new UserApplicationMail($user,$data->email,$data->credit_url,$data->fee,$data->event_date,$data->instructor_name,$data->course_name,$data->place_name,$data->count));
    }
}
