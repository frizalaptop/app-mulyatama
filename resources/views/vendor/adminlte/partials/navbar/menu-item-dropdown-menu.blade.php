<li @isset($item['id']) id="{{ $item['id'] }}" @endisset class="nav-item d-none d-sm-inline-block">

    {{-- Menu toggler --}}
    <a class="nav-link {{ $item['class'] }}" href=""
       data-toggle="dropdown" {!! $item['data-compiled'] ?? '' !!}>

        {{-- Icon (optional) --}}
        @isset($item['icon'])
            <i class="{{ $item['icon'] }} {{
                isset($item['icon_color']) ? 'text-' . $item['icon_color'] : ''
            }}"></i>
        @endisset

        {{-- Text --}}
        {{ $item['text'] }}
        <i class="bi bi-caret-down ml-1"></i>

        {{-- Label (optional) --}}
        @isset($item['label'])
            <span class="badge badge-{{ $item['label_color'] ?? 'primary' }}">
                {{ $item['label'] }}
            </span>
        @endisset

    </a>

    {{-- Menu items --}}
    <!-- <ul class="dropdown-menu border-0 shadow">
        @each('adminlte::partials.navbar.dropdown-item', $item['submenu'], 'item')
    </ul> -->

    {{-- Menu items --}}
    <div class="dropdown-menu dropdown-menu-sm dropdown-menu border-0 shadow">
        @foreach ($item['submenu'] as $subitem)
            <a href="{{ $subitem['url'] ?? '#' }}" class="dropdown-item">
                <i class="bi bi-caret-right mr-1"></i> {{ $subitem['text'] }}
            </a>
            @if (!$loop->last)
                <div class="dropdown-divider"></div>
            @endif
        @endforeach
    </div>

</li>
