<?php

namespace Litstack\Translations;

use Ignite\Crud\Filter\Filter;
use Ignite\Crud\Filter\FilterForm;
use Ignite\Support\AttributeBag;
use Illuminate\Database\Eloquent\Builder;
use Litstack\Translations\Models\Lang;

class GroupFilter extends Filter
{
    /**
     * Apply field attributes to query.
     *
     * @param Builder      $query
     * @param AttributeBag $attributes
     * @var   void
     */
    public function apply($query, AttributeBag $attributes)
    {
        if ($attributes->has('missing') && in_array('missing', $attributes->missing)) {
            $query->has('translations', '<', count(config('translatable.locales')));
        }

        if ($attributes->has('group')) {
            $query->where('group', $attributes->group);
        }
    }

    /**
     * Add filter form fields.
     *
     * @param  FilterForm $form
     * @return void
     */
    public function form(FilterForm $form)
    {
        $groups = Lang::select('group')->groupBy('group')->get()->map(fn ($lang) => $lang->group);

        $form->radio('group')->title(ucfirst(__lit('base.group')))->options($groups->toArray());

        $form->checkboxes('missing')->title(ucfirst(__lit('trans.missing')))->options(['missing']);
    }
}
