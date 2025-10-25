<?php

namespace Modules\Ad\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AdConversationParticipant extends Pivot
{
    protected $table = 'ad_conversation_user';
}
