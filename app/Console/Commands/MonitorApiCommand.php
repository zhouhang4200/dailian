<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class MonitorApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wz:monitor-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Api 监听';

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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        while (1) {
            try {
                $start =  microtime(true);

                $client = new Client(['timeout' => 1]);
                $response = $client->request('POST', 'http://js.qsios.cn/api/taobao/store');
                $result = $response->getBody()->getContents();

                $end = microtime(true);

                $data = [$result, '执行时间' => ($end - $start)*1000];

                myLog('monitorApi', $data);
            } catch (\Exception $exception) {
                myLog('monitorApiEx', $exception->getMessage());
            }
            sleep(1);
        }

    }
}
