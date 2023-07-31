<?php

namespace MartinRo\FilamentPageBlocks;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use MartinRo\FilamentPageBlocks\Facades\FilamentPageBlocks;
use ReflectionClass;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Finder\SplFileInfo;

class FilamentPageBlocksServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-page-blocks';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasCommands([
                Commands\MakePageBlockCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        $this->app->scoped('filament-page-blocks', function () {
            return new FilamentPageBlocksManager();
        });
    }

    public function bootingPackage(): void
    {
        $this->registerComponentsFromDirectory(
            PageBlock::class,
            [],
            app_path('Filament/PageBlocks'),
            'App\\Filament\\PageBlocks'
        );
    }

    protected function registerComponentsFromDirectory(string $baseClass, array $register, ?string $directory, ?string $namespace): void
    {
        if (blank($directory) || blank($namespace)) {
            return;
        }

        $filesystem = app(Filesystem::class);

        if ((! $filesystem->exists($directory)) && (! Str::of($directory)->contains('*'))) {
            return;
        }

        $namespace = Str::of($namespace);

        $register = array_merge(
            $register,
            collect($filesystem->allFiles($directory))
                ->map(function (SplFileInfo $file) use ($namespace): string {
                    $variableNamespace = $namespace->contains('*') ? str_ireplace(
                        ['\\'.$namespace->before('*'), $namespace->after('*')],
                        ['', ''],
                        Str::of($file->getPath())
                            ->after(base_path())
                            ->replace(['/'], ['\\']),
                    ) : null;

                    return (string) $namespace
                        ->append('\\', $file->getRelativePathname())
                        ->replace('*', $variableNamespace)
                        ->replace(['/', '.php'], ['\\', '']);
                })
                ->filter(fn (string $class): bool => is_subclass_of($class, $baseClass) && (! (new ReflectionClass($class))->isAbstract()))
                ->each(fn (string $class) => FilamentPageBlocks::register($class, $baseClass))
                ->all(),
        );
    }
}
