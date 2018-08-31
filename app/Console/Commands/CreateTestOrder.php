<?php

namespace App\Console\Commands;

use App\Models\Game;
use App\Models\Region;
use App\Models\Server;
use App\Services\OrderService;
use App\Models\GameLevelingType;
use Faker\Generator;
use Illuminate\Console\Command;

class CreateTestOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wanzi:CreateTestOrder {id} {count?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建测试订单数据';

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
     * @param Generator $generator
     * @throws \Exception
     */
    public function handle(Generator $generator)
    {
        $count = $this->argument('count') ?? 100000;

        for ($i=0; $i<= $count; $i ++) {

            $game = Game::find(rand(1, 18));
            $region = Region::where('game_id', $game->id)->first();
            $server = Server::where('region_id', $region->id)->first();
            $levelingType = GameLevelingType::where('game_id', $game->id)->first();

            OrderService::init($this->argument('id'))->create(
                $game->id,
                $region->id,
                $server->id,
                $generator->title,
                $generator->userName,
                $generator->password,
                $generator->firstName,
                random_int(1, 30),
                random_int(0, 24),
                $levelingType->id,
                rand(5, 250),
                rand(5, 1000),
                rand(5, 1000),
                rand(5, 1000),
                rand(5, 1000),
                rand(5, 1000),
                1,
                1,
                1,
                1,
                1,
                1,
                1,
                123456
            );

            $this->info($i);
        }
    }

}
