# Block-Based Page Builder for Filament

This is basically a lightweight version of [Z3d0X's](https://github.com/z3d0x) excellent [Filament Fabricator](https://filamentphp.com/plugins/fabricator) Plugin. 

It only provides the blocks functionality without layouts, pages, routing, etc.

## Installation

```bash
composer require martin-ro/filament-page-blocks
```

## Creating a block

```bash
php artisan make:page-block
```

## Using blocks in your template

```html
<x-filament-page-blocks::page-blocks :blocks="$page->blocks" />
```