<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ruangan;
use Illuminate\Support\Str;
use Carbon\Carbon;
class RotateKodeRuangan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rotate-kode-ruangan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   

public function handle()
{
    Ruangan::all()->each(function ($ruangan) {
        $ruangan->kode = strtoupper(Str::random(6));
        $ruangan->kode_expired_at = Carbon::now()->addHour();
        $ruangan->save();
    });
}
}
