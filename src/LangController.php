<?php

namespace Litstack\Translations;

use Ignite\Crud\Controllers\CrudController;
use Illuminate\Contracts\Auth\Access\Authorizable;

class LangController extends CrudController
{
    /**
     * Authorize request for authenticated lit-user and permission operation.
     * Operations: create, read, update, delete.
     *
     * @param  Authorizable $user
     * @param  string       $operation
     * @param  int          $id
     * @return bool
     */
    public function authorize(Authorizable $user, string $operation, $id = null): bool
    {
        if (is_null($group = config('lit-translations.permission-group'))) {
            return true;
        }

        return $user->can("{$operation} {$group}");
    }

    /**
     * Fill on update.
     *
     * @return void
     */
    public function fillOnUpdate($model)
    {
        // Clear cache for the updated translations.
        foreach (request()->payload as $locale => $value) {
            app('translation.loader')->clear($locale, $model->group, $model->namespace);
        }
    }
}
