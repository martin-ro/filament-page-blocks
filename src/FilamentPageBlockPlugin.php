<?php

namespace MartinRo\FilamentPageBlocks;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use MartinRo\FilamentPageBlocks\Forms\Components\PageBuilder;

class FilamentPageBlockPlugin implements Plugin
{
    use EvaluatesClosures;

    protected bool|Closure|null $shouldBeCollapsible = null;

    protected bool|Closure|null $shouldBeCollapsed = null;

    public function getId(): string
    {
        return 'filament-page-blocks';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        PageBuilder::configureUsing(function (PageBuilder $builder) {
            $builder->collapsible($this->shouldBeCollapsible())
                ->collapsed($this->shouldBeCollapsed());
        });
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function shouldBeCollapsible(): bool
    {
        return $this->evaluate($this->shouldBeCollapsible) ?? false;
    }

    public function collapsible(bool|Closure|null $condition = false): static
    {
        $this->shouldBeCollapsible = $condition;

        return $this;
    }

    public function shouldBeCollapsed(): bool
    {
        return $this->evaluate($this->shouldBeCollapsed) ?? false;
    }

    public function collapsed(bool|Closure|null $condition = false): static
    {
        $this->shouldBeCollapsed = $condition;

        return $this;
    }
}
