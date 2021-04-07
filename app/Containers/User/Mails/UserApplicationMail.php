<?php

namespace App\Containers\User\Mails;

use App\Containers\User\Models\User;
use App\Ship\Parents\Mails\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Ship\Transporters\DataTransporter;

/**
 * Class UserApplicationMail
 *
 * @author  Sebastian Weckend
 */
class UserApplicationMail extends Mail implements ShouldQueue
{

    use Queueable;

    /**
     * @var  string
     */
    protected $email;

    /**
     * @var string
     */

    /**
     * @var string
     */
    protected $credit_url;
    protected $fee;
    protected $event_date;
    protected $instructor_name;
    protected $course_name;
    protected $place_name;
    protected $count;

    protected $user;
  


    public function __construct(User $user, $email,$credit_url ,$fee ,$event_date, $instructor_name, $course_name, $place_name,$count )
    {
        $this->user = $user;
        $this->credit_url = $credit_url;
        $this->fee = $fee;
        $this->event_date = $event_date;
        $this->instructor_name = $instructor_name;
        $this->course_name = $course_name;
        $this->place_name = $place_name;
        $this->count = $count;
        $this->email = $email;
      
    }

    /**
     * @return  $this
     */
    public function build()
    {
        return $this->view('user::user-application-submit')
            ->to($this->email, $this->user->name)
            ->subject("お申込みを受け付けました　生前整理普及協会")
            ->with([
                'name' => $this->user->name,
                'email' => $this->user->email,
                'member_kananame' => $this->user->member_kananame,
                'postcode' => $this->user->postcode,
                'address1' => $this->user->address1,
                'address2' => $this->user->address2,
                'phone' => $this->user->phone,
                'companyname' => $this->user->companyname,
                'credit_url' => $this->credit_url,
                'fee' => $this->fee,
                'event_date' => $this->event_date,
                'instructor_name' => $this->instructor_name,
                'course_name' => $this->course_name,
                'place_name' => $this->place_name,
                'count' => $this->count,
            ]);
    }
}
