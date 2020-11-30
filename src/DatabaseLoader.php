<?php

namespace Litstack\Translations;

use Illuminate\Cache\CacheManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Litstack\Translations\Models\Lang;
use Litstack\Translations\Models\LangTranslation;

class DatabaseLoader extends FileLoader
{
    /**
     * CacheManager instance.
     *
     * @var CacheManager
     */
    protected $cache;

    /**
     * Create new DatabaseLoader instance.
     *
     * @param  CacheManager $cache
     * @return void
     */
    public function __construct(CacheManager $cache, Filesystem $files, $path)
    {
        $this->cache = $cache;

        parent::__construct($files, $path);
    }

    /**
     * Load the messages for the given locale.
     *
     * @param  string      $locale
     * @param  string      $group
     * @param  string|null $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        if (Str::startsWith($namespace, 'lit/')) {
            return parent::load($locale, $group, $namespace);
        }

        $key = $this->getCacheKey($locale, $group, $namespace);

        return $this->cache->rememberForever($key, function () use ($locale, $group, $namespace) {
            $dbTranslations = $this->getDatabaseTranslations($locale, $group, $namespace);

            if (! $dbTranslations->isEmpty()) {
                return $this->format($dbTranslations);
            }

            foreach (config('translatable.locales') as $l) {
                $this->store(
                    $l, $group, $namespace, parent::load($l, $group, $namespace)
                );
            }

            return parent::load($locale, $group, $namespace);
        });
    }

    /**
     * Clear translations from cache.
     *
     * @param  string      $locale
     * @param  string      $group
     * @param  string|null $namespace
     * @return array
     */
    public function clear($locale, $group, $namespace = null)
    {
        $this->cache->forget(
            $this->getCacheKey($locale, $group, $namespace)
        );
    }

    /**
     * Format collection database translations to the appropriate array that
     * the translator needs.
     *
     * @param  Collection $translations
     * @return array
     */
    protected function format(Collection $translations): array
    {
        $array = [];

        foreach ($translations as $translation) {
            Arr::set($array, $translation->lang->key, $translation->text);
        }

        return $array;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param  string      $locale
     * @param  string      $group
     * @param  string|null $namespace
     * @return string
     */
    protected function getCacheKey($locale, $group, $namespace = null)
    {
        return "lang.{$locale}.{$group}.{$namespace}";
    }

    /**
     * Get database translations.
     *
     * @param  string      $locale
     * @param  string      $group
     * @param  string|null $namespace
     * @return Collection
     */
    protected function getDatabaseTranslations($locale, $group, $namespace)
    {
        return LangTranslation::where('locale', $locale)
            ->whereHas('lang', function ($query) use ($group, $namespace) {
                $query->where('group', $group)
                    ->where('namespace', $namespace);
            })
            ->with('lang:id,key')
            ->get();
    }

    /**
     * Store translations to database.
     *
     * @param  string      $locale
     * @param  string      $group
     * @param  string|null $namespace
     * @param  array       $translations
     * @return void
     */
    protected function store($locale, $group, $namespace, array $translations)
    {
        foreach (Arr::dot($translations) as $key => $text) {
            // Skip empty arrays.
            if (is_array($text)) {
                continue;
            }

            $lang = Lang::firstOrCreate([
                'group'     => $group,
                'namespace' => $namespace,
                'key'       => $key,
            ]);

            LangTranslation::firstOrCreate([
                'lang_id' => $lang->id,
                'locale'  => $locale,
            ], [
                'text' => $text,
            ]);
        }
    }
}
