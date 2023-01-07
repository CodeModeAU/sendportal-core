<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Sendportal\Base\UpgradeMigration;

class AddDelayedSendAtToMessagesTable extends UpgradeMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $campaigns = $this->getTableName('messages');

        Schema::table($campaigns, function (Blueprint $table) {
            $table->timestamp('delayed_send_at')->nullable()->default(null)->index();
        });
    }
}
