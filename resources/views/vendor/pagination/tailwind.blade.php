@if ($paginator->hasPages())
    <div class="custom-pagination-wrapper">
        <div class="pagination-info">
            Menampilkan {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} 
            dari {{ $paginator->total() }}
        </div>

        <div class="pagination-container">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="page-btn disabled">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">‹</a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-dots">...</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-btn active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">›</a>
            @else
                <span class="page-btn disabled">›</span>
            @endif

        </div>
    </div>
@endif