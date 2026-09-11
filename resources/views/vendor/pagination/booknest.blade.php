@if ($paginator->hasPages())
<nav class="pagination" role="navigation" aria-label="Pagination">
    @if ($paginator->onFirstPage())
        <span class="pagination-arrow disabled" aria-disabled="true">←</span>
    @else
        <a class="pagination-arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">←</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pagination-dots">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                @else
                    <a class="pagination-number" href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a class="pagination-arrow" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">→</a>
    @else
        <span class="pagination-arrow disabled" aria-disabled="true">→</span>
    @endif
</nav>
@endif
