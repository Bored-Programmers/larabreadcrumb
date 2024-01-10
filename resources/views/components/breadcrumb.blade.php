@props([
    /** @var \App\Services\BreadcrumbLink[] */
    'breadcrumbs'
])

<div style="display: flex">
    @foreach (app(\BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService::class)->generate() as $breadcrumb)
        @php
            $title = str(__($breadcrumb->title))->ucfirst();
        @endphp

        <a
                style="white-space: pre"
                @if($breadcrumb->url) href="{{ $breadcrumb->url }}" @endif
        >{{ $title }} @if($loop->remaining)> @endif</a>
    @endforeach
</div>