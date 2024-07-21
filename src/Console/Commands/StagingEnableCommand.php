<?php

namespace StagingMode\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class StagingEnableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staging:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put the application into staging mode';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $newSecret = Str::random();

        $currentSecret = $this->laravel['config']['staging-mode.secret'];

        if (strlen($currentSecret) !== 0 && (! $this->confirm('Do you really wish to regenerate secret?'))) {
            return false;
        }

        $this->writeNewEnvironmentFileWith($newSecret);

        $this->info('You may bypass staging mode via [<comment>'.Config::get('app.url')."/{$newSecret}</comment>].");
    }

    /**
     * Write a new environment file with the given secret.
     */
    protected function writeNewEnvironmentFileWith(string $newSecret): void
    {
        $count = 0;
        $envContent = file_get_contents($this->laravel->environmentFilePath());
        $envContent = preg_replace(
            $this->replacementPattern(),
            'STAGING_MODE_SECRET='.$newSecret,
            $envContent,
            1,
            $count
        );

        if ($count === 0) {
            $envContent = $envContent.PHP_EOL."STAGING_MODE_SECRET={$newSecret}".PHP_EOL;
        }

        file_put_contents($this->laravel->environmentFilePath(), $envContent);
    }

    /**
     * Get a regex pattern that will match env STAGING_MODE_SECRET with any random string.
     */
    protected function replacementPattern(): string
    {
        $escaped = preg_quote('='.$this->laravel['config']['staging-mode.secret'], '/');

        return "/^STAGING_MODE_SECRET{$escaped}/m";
    }
}
