@if ($paginator->hasPages())
    <nav class="pagination-wrapper" role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination">
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline">Previous</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $start = max(1, $currentPage - 2);
                $end = min($lastPage, $currentPage + 2);
                
                // Adjust if near start
                if ($currentPage <= 3) {
                    $start = 1;
                    $end = min(5, $lastPage);
                }
                
                // Adjust if near end
                if ($currentPage >= $lastPage - 2) {
                    $start = max(1, $lastPage - 4);
                    $end = $lastPage;
                }
            @endphp

            {{-- First Page --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                
                @if ($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Page Numbers --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $currentPage)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last Page --}}
            @if ($end < $lastPage)
                @if ($end < $lastPage - 1)
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                @endif
                
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <span class="d-none d-sm-inline">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">
                        <span class="d-none d-sm-inline">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif