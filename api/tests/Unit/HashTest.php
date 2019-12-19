<?php

namespace Tests\Unit;

use App\Enum\CommonEnum;
use App\Utils\UtilHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class HashTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testHash()
    {
        $pwd = Str::random(20);
        echo $pwd;
        $hash = UtilHelper::hashPassword( $pwd);
        echo $hash;

        $this->assertGreaterThan(0, strlen($hash));
    }

    public function testSn()
    {
        $length = 20;
        $sn = UtilHelper::generateSn( CommonEnum::WITHDRAW_SN_PREFIX, $length);
        echo $sn, PHP_EOL;
        $this->assertEquals( strlen( $sn), 20);
        $nowUtc = UtilHelper::logTime();
        var_dump($nowUtc);
    }
}
