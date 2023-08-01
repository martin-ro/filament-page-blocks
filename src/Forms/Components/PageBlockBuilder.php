<?php

namespace MartinRo\FilamentPageBlocks\Forms\Components;

use Filament\Forms\Components\Builder;
use Illuminate\Support\HtmlString;
use MartinRo\FilamentPageBlocks\Facades\FilamentPageBlocks;

class PageBlockBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->blocks(FilamentPageBlocks::getPageBlocks());

        $this->label(fn () => new HtmlString('<h1 class="fi-header-heading text-xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-2xl">Page Blocks</h1>'));

        $this->addActionLabel('Add Block');

        $this->mutateDehydratedStateUsing(static function (?array $state): array {
            if (! is_array($state)) {
                return array_values([]);
            }

            $registerPageBlockNames = array_keys(FilamentPageBlocks::getPageBlocksRaw());

            return collect($state)
                ->filter(fn (array $block) => in_array($block['type'], $registerPageBlockNames, true))
                ->values()
                ->toArray();
        });
    }
}
