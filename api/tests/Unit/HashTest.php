<?php

namespace Tests\Unit;

use App\Enum\CommonEnum;
use App\Utils\UtilHelper;
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
        $pwd = 'test123'; // Str::random(20);
        echo $pwd, PHP_EOL;
        $hash = UtilHelper::hashPassword( $pwd);
        echo $hash, PHP_EOL;
        $h1 = password_hash($pwd, PASSWORD_BCRYPT);
        echo 'password_hash ', $h1, PHP_EOL;
        $this->assertGreaterThan(0, strlen($hash));
        $a = UtilHelper::validPassword($pwd, $hash);
        $this->assertTrue($a);
        $a1 = UtilHelper::validPassword($pwd, $h1);
        $this->assertTrue($a1);

        $p2= '$2y$04$hq14gU.ZbqugQpbOdCIQ5.e/5Gbp0pM0786b.EXQf2yHu.pc8RCHK';
        $a2 = UtilHelper::validPassword($pwd, $p2);
        $this->assertTrue($a2);

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
