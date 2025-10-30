@if ($paginator->hasPages())
    <ul class="pagntn d-flex mb-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><button type="button" class="btn">&laquo;</button></li>
        @else
            {{-- <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li> --}}
            <li><button type="button" class="btn" data-url="{{ $paginator->previousPageUrl() }}">&laquo;</button></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><button type="button" class="btn">{{ $element }}</button></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                <?php 
                $current_page = (isset($_GET['page']))?$_GET['page']:'1';
                ?>
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><button type="button" class="btn @if($page==$current_page) active @endif">{{ $page }}</button></li>
                    @else
                        {{-- <li><a href="{{ $url }}">{{ $page }}</a></li> --}}
                        <li><button type="button" class="btn" data-url="{{$url}}">{{ $page }}</button></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            {{-- <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li> --}}
            <li><button type="button" class="btn" data-url="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</button></li>
        @else
            <li class="disabled"><button type="button" class="btn">&raquo;</button></li>
        @endif
    </ul>
@endif
