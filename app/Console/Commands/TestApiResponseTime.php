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
        $requests = function ($total) {
            $uri = 'http://api.dailian.com/v1/games';
            for ($i = 0; $i < $total; $i++) {
                yield new Request('POST', $uri, [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer  eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjFkYWM5YjJiMDkxM2YzZjdhMjg5OWVjNTQ4MzI0MWQyNzFkZDVjYWNjZDhlZGZhZjljNDMzYTcyNTZjNzFiNWEwOGRkZjk5YWU0MTViMzllIn0.eyJhdWQiOiI3IiwianRpIjoiMWRhYzliMmIwOTEzZjNmN2EyODk5ZWM1NDgzMjQxZDI3MWRkNWNhY2NkOGVkZmFmOWM0MzNhNzI1NmM3MWI1YTA4ZGRmOTlhZTQxNWIzOWUiLCJpYXQiOjE1MzUwOTI5OTcsIm5iZiI6MTUzNTA5Mjk5NywiZXhwIjoxNTY2NjI4OTk3LCJzdWIiOiIyMSIsInNjb3BlcyI6W119.r5-XbaD2ee_h6-TBFShvy0Z_MNJ7feFei0Sk6ZZNNTOoa4ZbZtdg12iW109JZaKnYmMCT-3nW6OSA1bcaP1ejrcMfPp0wX5j9kSTVbfm4Fm2S94x4JS6-fw5MwVP5rULMi85ENdl4ze3Iq7CeZ7d31mBqHUFnEXFnB1WNsnRNtoXdmxO6myEALp_wgs6L6xkkWij95GGT0Lvk3ONcOwOyuv0MxnNDuDsXBmhh0FHsRlsPD4aJS8ga6Ihk9cIqLDRYu_vA_pxVYd8QNkPcmpUOEwpNS5G2MoH7_3WoEBI-DGE-x39sJ_4U_jXGHjd66LnyQrcNO1gfDGpVNUSMVVaSE6ZXdok9v7vq_HPf5Ace59ceiLNwQhd-zALKzJ9kgfiWlB--eue_9OiIu-243Hb8xGGAjPD0KlYtr7ap-iOmcCKl7azCvdTY0BFC5pIgP4n83DTbuQTe5-6a7RMk8BSPVXpc2mKU38qEKNd9FPv4mJf1tmMaxogL1GP-Er1qC9_h776Y9UqMKIAQTyxVusvCuu3PddL40KfoBkilZ8GZwR_qBoPha0rslsoiYW4baazL4TAkkiYhoUUPHOg60cqedP7oaAIxNKkLZAzzKc_4_BescOAc5b9n4iuCZfFgTP27_4ALoPk9XiM0HMyoPO4hzXgIYdsZCy57TmemnQs4PM',
                ]);
            }
        };

        $pool = new Pool($client, $requests(100), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) {
                echo $index.".";
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
