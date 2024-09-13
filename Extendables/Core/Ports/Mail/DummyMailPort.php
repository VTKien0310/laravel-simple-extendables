<?php

namespace App\Extendables\Core\Ports\Mail;

use App\Features\User\Models\User;
use Illuminate\Support\Collection;

class DummyMailPort implements MailPort
{
    /**
     * {@inheritDoc}
     */
    public function send(User|string|Collection $receivers, string $mailable, ...$mailableArgs): void
    {

    }

    /**
     * {@inheritDoc}
     */
    public function queueSend(User|string|Collection $receivers, string $mailable, ...$mailableArgs): void
    {

    }
}
