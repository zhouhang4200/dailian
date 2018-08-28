<?php

namespace App\Console\Commands;

use Carbon\Carbon;
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
//        // 读取文件中的值
//        $text = file_get_contents(public_path('api.txt'));
//        $arr = explode("\n", $text);
//
//        // 将原数组复制一份到第二数组,去除原数组的最后一个空值，和原数组倒数第二个值，
//        // 去掉第二个数组的第一个值和最后一个空值，好计算差
//        $arr2 = $arr;
//        array_splice($arr, -2); // 去最后2值，数组key重新排序
//        array_splice($arr2, -1); // 去最后值，数组key重新排序
//        array_splice($arr2, 0, 1); // 去第一个值，数组key重新排序
//
//        // 计算出微秒差值存入数组
//        $diff = [];
//        foreach ($arr as $k => $v) {
//            foreach ($arr2 as $k2 => $v2) {
//                $diff[$k] = $arr2[$k]-$v;
//            }
//        }
//
//        // 接口请求时间大于1秒的写入计数
//        $count = 0;
//        foreach ($diff as $k => $v) {
//            if ($v > 1) {
//                $count++;
//            }
//        }
//
//        dd($count);

        // 配置参数
        $times = 1000;
        $method = 'POST';
        $uri = 'http://api.dailian.com/v1/finance/flows/show';
        $options = [
            'query' => ['id' => 340, 'timestamp' => 213, 'sign' => 'ads']
        ];
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU5ZjgyNWJlMDk3NmFmZTZlYTk4MWJmNTQyZDI0YTVkMDUxMzI2ZmIwZDcyNDU0MGZlZjQ2OGY5YjBmM2RiOTZmNGE4MjJkZjdmMzc3MjZjIn0.eyJhdWQiOiI3IiwianRpIjoiNTlmODI1YmUwOTc2YWZlNmVhOTgxYmY1NDJkMjRhNWQwNTEzMjZmYjBkNzI0NTQwZmVmNDY4ZjliMGYzZGI5NmY0YTgyMmRmN2YzNzcyNmMiLCJpYXQiOjE1MzU0MjMwOTUsIm5iZiI6MTUzNTQyMzA5NSwiZXhwIjoxNTY2OTU5MDk1LCJzdWIiOiIyMSIsInNjb3BlcyI6W119.TSFvQkkQO3A4KH-SzMpJ083ExS3DSEKrW_LcwCjZbENStpjfzdu0FlsYu1rj9oJIvO5ISNct7rfFkLwbbJyj7FbYmRmUGLd3sLW9rL5135JGulyn0He3DOiHn5ZRKZAwnEeGKW_hWCpRJpx6sMzYyGulx7rQyVxls5otGHgQsCcAR3LWEU2AeDUmYBiAnSbU5KD8IQalMscyphbPjce0s2lXXt4zqo2zhvY1iPKn5Kk3uzRwO-tCAjgsyq5BrAqexCwBnrvBtzUjqZBFSttBFlAQjSL8T2LPNJTe0JgX4xHcMzYOwy5ievRhRm3y96IOOm86ikLyuUeKod44zAtQUdjLR4ot1BKNndKKvuFd7-EahBb_hEqfEs3SAcT4b_MeZHdVAXTR1R5IoQWAFOsudOkm-yeLIc_lA2pa9SIg2DXcjqXi8KxkqusSaQkFhjUGRtJxxpsx4qChNorU1d_jEVAw65c-VcEVNemB5GSKu_xTu3QXDqHcHT55xfnYoWC8YsecttUbvFb8kMmRQzrsaEnorR7UboUVnS6X1zoNoNr_k0Ya_Yl6iGcuZxNNh_KQ8xr6UzH-ypH19Qk0nDoJNu3sklcBBHe57ici0F28UgNZwT24gIqJE1KAJvohbIcbp9rQ0o5_baJC1grUplegUKYOPtYqoXWPASk1ta4hV1A'
        ];

        // 初始化
        $client = new Client([
            'headers' => $headers
        ]);

        // 异步请求
        $requests = function ($total) use ($client, $method, $uri, $options) {
            for ($i = 0; $i < $total; $i++) {
                yield function () use ($client, $method, $uri, $i, $options) {
                    return $client->requestAsync($method, $uri, $options);
                };
            }
        };

        $startTime = Carbon::now()->timestamp.'.'.Carbon::now()->micro;
        // 结果
        $pool = new Pool($client, $requests($times), [
            'concurrency' => 5,
            'fulfilled' => function ($response, $index) use ($startTime) {
                // 得出接口请求的微秒时间戳，存入文件
                $time = Carbon::now()->timestamp.'.'.Carbon::now()->micro;
                $fileName = public_path('api.txt');
                $file = fopen($fileName, 'a');
                fwrite($file, $time."\n");
            },
            'rejected' => function ($reason, $index) {
            },
        ]);

        $promise = $pool->promise();

        $promise->wait();
    }
}
