<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $token;
    protected $password;

    /**
     * Create a new job instance.
     */
    public function __construct($email, $token, $password)
    {
        $this->email = $email;
        $this->token = $token;
        $this->password = $password;
    }


    /**
     * Execute the job.
     */


    public function handle()
    {

        $tokenRecord = DB::table('password_reset_tokens')
        ->where('email', $this->email)
        ->where('token', $this->token)
        ->first();

        if (!$tokenRecord) {
            Log::error("Invalid or expired token for: {$this->email}");
            return;
        }

        $user = User::where('email', $this->email)->first();

        $user->forceFill([
            'password' => Hash::make($this->password),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        Log::info("Password successfully reset for: {$this->email}");
    }

}
