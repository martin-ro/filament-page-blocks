<?php

namespace MartinRo\FilamentPageBlocks\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Filament\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakePageBlockCommand extends Command
{
    use CanManipulateFiles;
    use CanValidateInput;

    protected $signature = 'make:page-block {name?} {--F|force}';

    protected $description = 'Create a new filament page block';

    public function handle(): int
    {
        $pageBlock = (string) Str::of($this->argument('name') ?? $this->askRequired('Name (e.g. `HeroBlock`)', 'name'))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        $pageBlockClass = (string) Str::of($pageBlock)->afterLast('\\');

        $pageBlockNamespace = Str::of($pageBlock)->contains('\\') ?
            (string) Str::of($pageBlock)->beforeLast('\\') :
            '';

        $label = Str::of($pageBlock)
            ->beforeLast('Block')
            ->explode('\\')
            ->map(fn ($segment) => Str::title($segment))
            ->implode(': ');

        $shortName = Str::of($pageBlock)
            ->beforeLast('Block')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $view = Str::of($pageBlock)
            ->beforeLast('Block')
            ->prepend('components\\page-blocks\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = app_path(
            (string) Str::of($pageBlock)
                ->prepend('Filament\\PageBlocks\\')
                ->replace('\\', '/')
                ->append('.php'),
        );

        $viewPath = resource_path(
            (string) Str::of($view)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        $files = [$path, $viewPath];

        if (! $this->option('force') && $this->checkForCollision($files)) {
            return static::INVALID;
        }

        $this->copyStubToApp('PageBlock', $path, [
            'class' => $pageBlockClass,
            'namespace' => 'App\\Filament\\PageBlocks'.($pageBlockNamespace !== '' ? "\\{$pageBlockNamespace}" : ''),
            'label' => $label,
            'shortName' => $shortName,
        ]);

        $this->copyStubToApp('PageBlockView', $viewPath);

        $this->info("Successfully created {$pageBlock}!");

        return static::SUCCESS;
    }
}
