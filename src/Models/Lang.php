<?php

namespace Litstack\Translations\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Ignite\Crud\Models\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;

class Lang extends Model implements TranslatableContract
{
    use Translatable;

    /**
     * Database table name.
     *
     * @var string
     */
    public $table = 'lang';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['group', 'namespace', 'key'];

    /**
     * The attributes to be translated.
     *
     * @var array
     */
    public $translatedAttributes = ['text'];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Translation model class.
     *
     * @var string
     */
    protected $translationModel = LangTranslation::class;
}
