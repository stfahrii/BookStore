@if ($paginator->hasPages() || $paginator->total() > 0)
    <div class="pagination-container">
        <div class="pagination-info">
            Menampilkan <strong>{{ $paginator->firstItem() ?? 0 }}</strong> - <strong>{{ $paginator->lastItem() ?? 0 }}</strong> dari total <strong>{{ $paginator->total() }}</strong> buku (Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }})
        </div>

        @if ($paginator->hasPages())
            <div class="pagination-nav">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="page-btn disabled">&laquo; Prev</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev">&laquo; Prev</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="page-btn disabled">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
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

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next">Next &raquo;</a>
                @else
                    <span class="page-btn disabled">Next &raquo;</span>
                @endif
            </div>
        @endif
    </div>
@endif
