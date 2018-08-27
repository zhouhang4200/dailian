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
        $client = new Request([
            'POST',
            'http://api.dailian.com/v1/finance/flows/show',
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImY5ZTg1Zjg3OTUyNDAyZTFhNjdkZmRkMTQ2ZWRkYzA1NTE4MDNjYzRiNzE1MTQ2ZjQ4MDVmMDFlZWI1NDZmYjNjMzk1OGNkMjU1OWY1NjA1In0.eyJhdWQiOiI3IiwianRpIjoiZjllODVmODc5NTI0MDJlMWE2N2RmZGQxNDZlZGRjMDU1MTgwM2NjNGI3MTUxNDZmNDgwNWYwMWVlYjU0NmZiM2MzOTU4Y2QyNTU5ZjU2MDUiLCJpYXQiOjE1MzUzNDg5MzYsIm5iZiI6MTUzNTM0ODkzNiwiZXhwIjoxNTY2ODg0OTM1LCJzdWIiOiIyMSIsInNjb3BlcyI6W119.mX_Bt_QCmf50MwZ6b4e5Jxp4f6mfP7NoExzr7EzMOf6LaO1DIGwacrdXmnS-64s6S6hPXjU-6ifgfPZZ4r8rkaIQP7iIXaqKnIyue57QbGTDJ7ExucnJVqXldCgLh4p-fUq5sq-PUySVybk79hnTOzG0qO9dps7qdNY8Ofo-K3F4sS50nLqJco9eUqMQvQRjWlGJCB3tRMaTKqbSz19WwzyFHCv-MiXv71cL8OPickpkdS9PhruZZEJcavYp6olIIt6nEA3U5UIXlCBK09tiVoCPMItv0VGn3tpOL0-U76gsw_aJN7E-tHHZo9igv4pI_nVaVaAqFllpgreDt2tkqAlmH7wT8yrDaF5zSkFs8ZXMXHfNdyOF7r3Z_KimpNJSLsrpzy0Dxlt2UScjY0EpMvAINjiY40snHB52GkFJF27Su8s3-9bzw9SjMpL020OFpfjU09NsgOtFSbBQ_tlMjZLGKoAMzi8yIe2qGeJwOqsmxUwTZIs0Kuy8x6sHOuY2wPaAih7ClL-GmlFcSp6qdG4Z18DnNtvBZbPTgctDhEiMn3POyVUl-oG_SpZUkCAZN1g_DE5nHlkgZqu62L_5zFJK6LLXLnQ8O7XJLDIgPm591aGoNQD2tuyOMulVkMDye2hAhV_mSsa4rI_QTJoKdJBZ7KM1fNhu4QYM8Ly6_EM'
            ]
        ]);
        $requests = function ($total) use ($client) {
            $uri = 'http://api.dailian.com/v1/finance/flows/show';
            for ($i = 0; $i < $total; $i++) {
                yield function () use ($client, $uri, $i) {
                    return $client->requestAsync('POST', $uri, [
                        'form-params' => ['id' => 340, 'timestamp' => 213, 'sign' => 'ads']
                    ]);
                };
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
