<?php

namespace Litstack\Translations\Commands;

use Illuminate\Console\Command;
use Litstack\Translations\DatabaseLoader;
use Litstack\Translations\Models\LangTranslation;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear translation cache.';

    /**
     * Database translation loader instance.
     *
     * @var DatabaseLoader
     */
    protected $loader;

    /**
     * Create a new command instance.
     *
     * @param  DatabaseLoader $loader
     * @return void
     */
    public function __construct(DatabaseLoader $loader)
    {
        parent::__construct();

        $this->loader = $loader;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (LangTranslation::with('lang')->get() as $translation) {
            $this->loader->clear(
                $translation->locale,
                $translation->lang->group,
                $translation->lang->namespace,
            );
        }

        return 0;
    }
}
