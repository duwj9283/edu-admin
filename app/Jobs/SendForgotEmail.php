<?php
namespace App\Jobs;

use App\Jobs\Job;
use App\Models\User;
use App\Models\UserCode;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendForgotEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = $this->user->email;
        $code = randStr(6, 1);

        $row = new UserCode;
        $row->email = $email;
        $row->code = $code;
        $row->expired_at = date('Y-m-d H:i:s', time() + 600);
        $row->save();

        $mailer->send('emails.forgot', ['code' => $code], function ($m) use ($email) {
            $m->from('kf@omeeting.com', '魅课网');
            $m->to($email)->subject('找回登录密码');
        });
    }
}
