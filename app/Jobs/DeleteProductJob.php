<?php namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;

class DeleteProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $shopDomain;

    public $data;


    public function __construct(string $shopDomain, stdClass $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    public function handle() {

        $user = User::firstWhere('name', $this->shopDomain);
        if ($user->id) {
            $user->shopify_products()->where('id', $this->data->id)->delete();
            $user->updateStats();
        }

    }
}
