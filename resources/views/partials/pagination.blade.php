@if ($paginator->hasPages())
    <div class="pagination">
        <span class="pagination-info">แสดง {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} จาก {{ $paginator->total() }} รายการ</span>
        <div class="pagination-links">
            <a class="pagination-link {{ $paginator->onFirstPage() ? 'disabled' : '' }}" href="{{ $paginator->previousPageUrl() ?: '#' }}" aria-label="ก่อนหน้า">←</a>
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pagination-link disabled">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <a class="pagination-link {{ $page == $paginator->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                    @endforeach
                @endif
            @endforeach
            <a class="pagination-link {{ $paginator->hasMorePages() ? '' : 'disabled' }}" href="{{ $paginator->nextPageUrl() ?: '#' }}" aria-label="ถัดไป">→</a>
        </div>
    </div>
@endif
