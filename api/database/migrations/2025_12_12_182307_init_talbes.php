<?php

use App\Enum\UserEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class InitTalbes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('admin_user');

        Schema::create('admin_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name', 50)->comment('用户名,可做登录')->index('idx_admin_user_name');
            $table->string('passwd', 64)->comment('哈希后的密码');
            $table->string('real_name', 50)->default('')->comment('姓名');
            $table->integer('login_count')->default(0)->comment('登录次数');
            $table->string('last_login_time', 50)->default('')->comment('最近登录日期');
            $table->tinyInteger('is_disabled')->default(0)->comment('是否禁用:0=否,1=是');
            $table->timestamp('create_time')->useCurrent()->index('idx_admin_ctime');;
            $table->timestamp('update_time')->useCurrent();
        });
        $model = new \App\Models\AdminUserModel();
        $model->user_name = 'admin';
        $model->passwd = \App\Utils\UtilHelper::hashPassword('admin');
        $model->real_name = 'admin';
        $model->save();

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_name', 64)->comment('用户名,可做登录')->unique()->index('idx_user_name');
            $table->string('email', 64)->default('')->comment('Email,可做登录')->index('idx_user_email');
            $table->string('mobile', 20)->default('')->comment('手机号，可做登录')->index('idx_user_mobile');
            $table->string('passwd', 64)->default('')->comment('哈希后的密码');
            $table->string('nick_name', 64)->default('')->comment('昵称');
            $table->tinyInteger('gender')->default(0)->comment('性别:0=未设置，1=男，2=女');
            $table->string('birthday', 24)->default('')->comment('出生年月');
            $table->integer('login_count')->default(0)->comment('登录次数');
            $table->timestamp('last_login_time')->nullable()->comment('最近登录日期');
            $table->tinyInteger('status')->default(0)->comment('状态:1=待审核、2=审核通过、3=驳回');
            $table->string('track_code', 64)->default('')->comment('用于追踪订单的追踪码');
            $table->tinyInteger('is_deleted')->default(0)->comment('del:0=no，1=yes');
            $table->timestamp('create_time')->useCurrent()->index('idx_user_ctime');
            $table->timestamp('update_time')->useCurrent();
        });

        $model = new \App\Models\UsersModel();
        $model->user_name = 'test';
        $model->passwd = \App\Utils\UtilHelper::hashPassword('test123');
        $model->status = UserEnum::STATUS_PASS;
        $userId = DB::table('users')->insertGetId($model->getAttributes());

        Schema::dropIfExists('user_balance');
        Schema::create('user_balance', function (Blueprint $table) {
            $table->bigInteger('user_id')->comment('所属用户')->primary();
            $table->float('balance', 10, 2)->default('0')->comment('余额（可提现金额）');
            $table->float('frozen', 10, 2)->default(0)->comment('冻结金额（提现中）');
            $table->float('withdraw', 10, 2)->default(0)->comment('已提现金额');
            $table->timestamp('create_time')->useCurrent()->index('idx_user_balance_ctime');
            $table->timestamp('update_time')->useCurrent();
        });
        $model = new \App\Models\UserBalanceModel();
        $model->user_id = $userId;
        $model->balance = 0;
        $model->save();

        Schema::dropIfExists('banner');
        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 50)->comment('标题')->index('idx_banner_title');
            $table->string('pic_url', 256)->default('')->comment('图片URL');
            $table->tinyInteger('status')->default(0)->comment('状态:1=已上架,2=已下架');
            $table->string('redirect_url', 256)->default('')->comment('跳转 URL');
            $table->string('sku', 10)->default('')->index('idx_banner_sku');;
            $table->tinyInteger('return_type')->default(0)->comment('返佣方式:1=金额,2=比例');
            $table->float('return_value', 10, 2)->default('0')->comment('返佣金额/比例');
            $table->float('price', 10, 2)->default(0);
            $table->tinyInteger('is_delete')->default(0);
            $table->timestamp('create_time')->useCurrent()->index('idx_banner_ctime');
            $table->timestamp('update_time')->useCurrent();
        });

        Schema::dropIfExists('order_info');
        Schema::create('order_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('所属用户')->index('idx_order_info_user_id');
            $table->string('order_sn', 36)->comment('订单号')->index('idx_order_info_sn');
            $table->float('order_amount', 10, 2)->default(0)->comment('订单金额（不含运费）');
            $table->float('commission', 10, 2)->default(0)->comment('订单佣金');
            $table->tinyInteger('order_status')->default(0)->comment('订单状态:1=未付款、2=已取消、3=已付款、4=已发货');
            $table->timestamp('order_time')->comment('下单日期')->useCurrent();
            $table->timestamp('pay_time')->comment('支付日期')->nullable();
            $table->timestamp('deliver_time')->comment('发货日期')->nullable()->index('idx_order_info_deliver_time');
            $table->tinyInteger('commission_status')->comment('佣金状态:1=未发放、2=已发放、3=不发放');
            $table->integer('grant_days')->default(30)->comment('佣金发放周期,默认30天');
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('update_time')->useCurrent();
            $table->index(['order_time', 'order_status'], 'idx_order_info_time_status');
            $table->index(['pay_time', 'order_status'], 'idx_order_info_pay_time_status');
        });

        Schema::dropIfExists('order_goods');

        Schema::create('order_goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->comment('所属用户')->index('idx_order_goods_order_id');
            $table->string('order_sn', 36)->comment('订单号')->index('idx_order_goods_order_sn');
            $table->string('sku', 36)->default('')->comment('SKU');
            $table->integer('sku_quantity')->default(0)->comment('SKU数量');
            $table->float('sku_price', 10, 2)->comment('单价');
            $table->float('subtotal')->comment('小计金额=SKU单价 x SKU数量');
            $table->float('commission_ratio')->comment('佣金比例');
            $table->float('sku_commission')->comment('sku佣金=小计金额x(佣金比例/100)');
            $table->timestamp('create_time')->useCurrent();
            $table->timestamp('update_time')->useCurrent();
        });

        Schema::dropIfExists('finance_withdraw');

        Schema::create('finance_withdraw', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sn', 36)->comment('提现单号')->index('idx_finance_withdraw_sn');
            $table->bigInteger('user_id')->comment('所属用户')->index('idx_finance_withdraw_user_id');
            $table->float('amount', 10, 2)->comment('提现金额');
            $table->tinyInteger('status')->comment('提现状态:1=未审核、2=驳回、3=提现成功');
            $table->tinyInteger('way')->comment('提现方式：1=银行卡，2=支付宝');
            $table->string('card', 36)->comment('提现银行卡号或支付宝账号');
            $table->string('name', 24)->comment('收款人姓名');
            $table->string('remark', 256)->comment('备注');
            $table->timestamp('create_time')->useCurrent()->index('idx_finance_withdraw_ctime');
            $table->timestamp('update_time')->useCurrent();
        });


        Schema::dropIfExists('finance_trade');

        Schema::create('finance_trade', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->comment('所属用户')->index('idx_finance_trade_user_id');
            $table->string('business_sn', 36)->comment('收支相关单号,如订单号,提现号')->index('idx_finance_business_sn');
            $table->float('amount', 10, 2)->comment('金额');
            $table->tinyInteger('type')->comment('收支类型：1=收入、2=提现');

            $table->timestamp('create_time')->useCurrent()->index('idx_finance_trade_ctime');
            $table->timestamp('update_time')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('user_balance');
        Schema::dropIfExists('order_info');
        Schema::dropIfExists('order_goods');
        Schema::dropIfExists('finance_withdraw');
        Schema::dropIfExists('finance_trade');
    }
}
