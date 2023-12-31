@props(['blocks' => []])

@foreach ($blocks as $blockData)
    @php
        $pageBlock = \MartinRo\FilamentPageBlocks\Facades\FilamentPageBlocks::getPageBlockFromName($blockData['type'])
    @endphp

    @isset($pageBlock)
        <x-dynamic-component
            :component="$pageBlock::getComponent()"
            :attributes="new \Illuminate\View\ComponentAttributeBag($pageBlock::mutateData($blockData['data']))"
        />
    @endisset
@endforeach
