<?php

namespace Modules\Auth\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Metti\LaravelSms\Facade\SendSMS;

class SendSmsMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 5;

    public int $backoff = 60;

    public function __construct(
        private readonly string $mobile,
        private readonly string $pattern,
        private readonly array $parameters
    ) {
    }

    public function handle(): void
    {
        SendSMS::via('ippanel')
            ->patternMessage($this->pattern, $this->parameters)
            ->recipients([$this->mobile])
            ->send();
    }
}
