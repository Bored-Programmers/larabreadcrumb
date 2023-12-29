@props([
    /** @var \App\Services\BreadcrumbLink[] */
    'breadcrumbs'
])

<div style="display: flex">
    @foreach ($breadcrumbs as $breadcrumb)
        <a
                style="white-space: pre"
                href="{{ $breadcrumb->url }}"
        >{{ str(__($breadcrumb->title))->ucfirst() }} @if($loop->remaining)> @endif</a>
    @endforeach
</div>