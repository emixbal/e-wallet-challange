<?php

namespace App\Jobs;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TopupWalletJob implements ShouldQueue
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
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $this->userId],
                ['balance' => 0]// initial balance if wallet is created
            );

            // Lock the wallet for update and update the balance
            $wallet->lockForUpdate();
            $wallet->balance += $this->amount;
            $wallet->save();
        });

    }
}
