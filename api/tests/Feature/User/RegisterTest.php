<?php
/**
 *
 * Author: zhaobin
 * Date: 2019-11-08
 * Time: 00:50
 */

namespace Tests\Feature\User;


use App\Services\Frontend\UserService;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testService()
    {
        $model = UserService::singleton()->findByUserName('test');
        $this->assertIsObject($model);
    }
}