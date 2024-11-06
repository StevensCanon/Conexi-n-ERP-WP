<?php 

namespace App\Jobs;

use App\Services\ErpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class SyncOrdersToErp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orders;
    protected $erpService;

    public function __construct($orders, ErpService $erpService)
    {
        $this->orders = $orders;
        $this->erpService = $erpService;
    }

    public function handle()
    {
        foreach ($this->orders as $order) {
            $id = $order['id'];
            
            Log::info("Processing order ID: $id");
    
            if (!$this->erpService->orderExists($id)) {
                $response = $this->erpService->sendToErp($order);
                Log::info("Response from ERP: ", $response);
            } else {
                $response = $this->erpService->updateOrder($id, $order);
                Log::info("Updated order in ERP: ", $response);
            }
        }
    }
}
