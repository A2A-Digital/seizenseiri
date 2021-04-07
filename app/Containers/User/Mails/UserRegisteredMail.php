<?php

namespace App\Containers\User\Mails;

use App\Containers\User\Models\User;
use App\Ship\Parents\Mails\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserRegisteredMail extends Mail implements ShouldQueue
{
    use Queueable;

    /**
     * @var  \App\Containers\User\Models\User
     */
    protected $user;

    /**
     * UserRegisteredNotification constructor.
     *
     * @param \App\Containers\User\Models\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('user::user-registered')
            ->to($this->user->email, $this->user->name)
            ->subject("ご登録完了のお知らせ　生前整理普及協会")
            ->with([
                'name' => $this->user->name,
                'member_number' => $this->user->name
            ]);
    }
}
