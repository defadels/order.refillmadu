{{-- For submenu --}}
<ul class="menu-content">
    @foreach($menu as $submenu)
    <?php
            $submenuTranslation = "";
            if(isset($menu->i18n)){
                $submenuTranslation = $menu->i18n;
            }
        ?>

    @canany(isset($submenu->permission)?$submenu->permission:true)
    @if(isset($submenu->route_is))
    <li class="{{ (request()->routeIs($submenu->route_is)) ? 'active' : '' }}">
    @else
    <li class="{{ (request()->is($submenu->url)) ? 'active' : '' }}">
    @endif
        @if(isset($submenu->route))
        <a href="{{ route($submenu->route) }}">
        @else
        <a href="{{ $submenu->url }}">
        @endif
            <i class="{{ isset($submenu->icon) ? $submenu->icon : "" }}"></i>
            <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ __('locale.'.$submenu->name) }}</span>
        </a>
        @if (isset($submenu->submenu))
        @include('panels/submenu', ['menu' => $submenu->submenu])
        @endif
    </li>
@endcanany

    @endforeach
</ul>
