<?php

namespace Litstack\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LangTranslation extends Model
{
    /**
     * Whether the model uses the default timestamp columns.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['lang_id', 'locale', 'text'];

    /**
     * Lang relationship.
     *
     * @return BelongsTo
     */
    public function lang(): BelongsTo
    {
        return $this->belongsTo(Lang::class);
    }
}
