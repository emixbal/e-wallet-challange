<?php

namespace App\Jobs;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class WithdrawJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $amount;

    public function __construct($userId, $amount)
    {
        $this->userId = $userId;
        $this->amount = $amount;
    }

    public function handle()
    {
        DB::transaction(function () {
            $wallet = Wallet::where(['user_id' => $this->userId])->first();
            $wallet->balance -= $this->amount;

            if ($wallet->balance < 0) {
                throw new \Exception('Insufficient funds.');
            }

            $wallet->save();
        });
    }
}
