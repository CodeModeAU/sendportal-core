<?php

declare(strict_types=1);

namespace Sendportal\Base\Services\Campaigns;

use Carbon\Carbon;
use Sendportal\Base\Models\Campaign;

class MessageDelayResolver
{

    public function calculateDelay(Carbon $offset, ?Campaign $campaign): ?Carbon
    {
        return $offset->clone();
    }
}
