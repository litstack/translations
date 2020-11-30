<?php

namespace Litstack\Translations;

use Ignite\Crud\Config\CrudConfig;
use Ignite\Crud\CrudIndex;
use Litstack\Translations\Models\Lang;

class LangConfig extends CrudConfig
{
    /**
     * Model class.
     *
     * @var string
     */
    public $model = Lang::class;

    /**
     * Controller class.
     *
     * @var string
     */
    public $controller = LangController::class;

    /**
     * Model singular and plural name.
     *
     * @param Lang|null lang
     * @return array
     */
    public function names(Lang $lang = null)
    {
        return [
            'singular' => ucfirst(__lit('trans.translation')),
            'plural'   => ucfirst(__lit('trans.translations')),
        ];
    }

    /**
     * Get crud route prefix.
     *
     * @return string
     */
    public function routePrefix()
    {
        return 'translations';
    }

    /**
     * Build index page.
     *
     * @param  \Ignite\Crud\CrudIndex $page
     * @return void
     */
    public function index(CrudIndex $page)
    {
        $page->navigationRight()->component('lit-crud-language');

        $page->table(function ($table) {
            $table->col(ucfirst(__lit('base.group')))
                ->value('<span class="badge badge-light">{group}</span>')
                ->sortBy('group')
                ->small()
                ->center(false);
            $table->col(ucfirst(__lit('trans.key')))
                ->value('{key}')
                ->sortBy('key')
                ->small()
                ->center(false);
            $table->field(ucfirst(__lit('trans.translation')))
                ->input('text')
                ->translatable();
        })
        ->query(function ($query) {
            $query->with('translations');
        })
        ->filter([
            ucfirst(__lit('base.filter')) => GroupFilter::class,
        ])
        ->search('key', 'translations.text');
    }
}
