# Block-Based Page Builder for Filament

This is basically a lightweight version of [Z3d0X's](https://github.com/z3d0x) excellent [Filament Fabricator](https://filamentphp.com/plugins/fabricator) Plugin. 

It only provides the blocks functionality without layouts, pages, routing, etc.

## Installation
You can install this package via composer:
```bash
composer require martin-ro/filament-page-blocks
```

## Documentation
Documentation can be viewed at: https://filamentphp.com/plugins/fabricator


## Creating a Page Block

```bash
php artisan make:filament-page-block MyPageBlock
```

This will create the following Page Block class:

```php
use Filament\Forms\Components\Builder\Block;
use MartinRo\FilamentPageBlocks\PageBlock;
 
class MyBlock extends PageBlock
{
    public static function getBlockSchema(): Block
    {
        return Block::make('my-page-block')
            ->label('My Page Block')
            ->icon('heroicon-o-rectangle-stack')
            ->schema([
                //
            ]);
    }
 
    public static function mutateData(array $data): array
    {
        return $data;
    }
}
```

and its corresponding blade component view:
```html
@props([
    //
])

<div>
    //
</div>

```

## Using Page Blocks in your template

```html
<x-filament-page-blocks::page-blocks :blocks="$page->blocks" />
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Ziyaan Hassan](https://github.com/Z3d0X)
- [Martin Ro](https://github.com/martin-ro)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
