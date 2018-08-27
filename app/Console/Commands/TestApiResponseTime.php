<?php

namespace App\Console\Commands;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class TestApiResponseTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();
        $requests = function ($total) use ($client) {
            $uri = 'http://api.dailian.com/v1/finance/flows/show';
            for ($i = 0; $i < $total; $i++) {
                $client->requestAsync('POST', $uri, [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer  eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEzMTA5MWQ4ODZhMDYxYTllNTBmODExN2M2ZWViMTkyOGVjNTMwMmI4NzA1NDdjMGU0MDNlNGIxNTVmMTA0YjJiMjQ0MmFiMzg0YjcxNDE1In0.eyJhdWQiOiI3IiwianRpIjoiMTMxMDkxZDg4NmEwNjFhOWU1MGY4MTE3YzZlZWIxOTI4ZWM1MzAyYjg3MDU0N2MwZTQwM2U0YjE1NWYxMDRiMmIyNDQyYWIzODRiNzE0MTUiLCJpYXQiOjE1MzUzMzczOTEsIm5iZiI6MTUzNTMzNzM5MSwiZXhwIjoxNTY2ODczMzkwLCJzdWIiOiIyMSIsInNjb3BlcyI6W119.HGBIbadvI-vE5aCw55k9jJIIkadZliYPIC1dD9_lTnUrvXyu6SwcHRJ0l3Vk2GMHTegwDij3ZDn52tWzHBYOfO4ziMYTrlI1SQs0-p56Cei53-SSA3XtuuYSQQsd7BcbU_E8XoVLlWaKHGK5SRVy_znELM4Hwvb8Bu7IUZ4ajc7uGa_2xZ4BBqzahDFOUa-P9JfvBA6znssHzmrJr0DEjYoM5zjU14oC9NKu_o7zxnHwXkVQmkXGKpuRSL_ETY2j_N1SKVVIAgoq8nNaaOckyTqDQN6GUm2KWp7m4RO56j7AqO7PMEgAR-bmFvqDuU3OQ66zMQKKsbfw0QcrceeVaWp5Enu6W1ilbYPYEgO9Y_T34oJR8n31OpfIqFLq-X6lG8el-yx5Eqrq76ogxx4U2EsOZhmaz63gIumFJnceYUcPsPQtGhor6n3_FvjxCVHjoUpd6-JSSXeySBL94QanijEq7LoJ76dMrrn_JZLccjw1KtlxMyTN1WYjOFeprgH5FIcgOA13ZEe2PN5dGOu2sQMKUd4R-2cG-XNIhVc1B7xYJ5gy82co-uDPNhsPyKmVmy3vepg1M4IGEGKyzG1t90raTCrjlVUzCl3xv7GuUQpAX__Ix1n2a771TV6fFWiKJodOLZdhs0riBTrUAKKFvKvjSs91eXDUR2azg_W37Uw',
                    'form-params' => ['id' => 340, 'timestamp' => 213, 'sign' => 'ads']
                ]);
            }
        };

        $pool = new Pool($client, $requests(5), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                echo $response->getBody()->getContents();
                // this is delivered each successful response
            },
            'rejected' => function ($reason, $index) {

                // this is delivered each failed request
            },
        ]);

// Initiate the transfers and create a promise
        $promise = $pool->promise();

// Force the pool of requests to complete.
        $promise->wait();
    }
}
