<nav class="main-menu">
    <ul class="mt-4">
        @can('manage-user')
        <li>
            <a class="nav-link @if (isset($nl_users_class)) {{ $nl_users_class }} @endif" href="{{ route('users') }}">
                <i class="bi bi-people-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Users') }}
                </span>
            </a>
        </li>
        @endcan
        @can('manage-category')
        <li>
            <a class="nav-link @if (isset($nl_category_class)) {{ $nl_category_class }} @endif" href="{{ route('category.list') }}">
                <i class="bi bi-folder-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Categories') }}
                </span>
            </a>
        </li>
        @endcan
        @can('manage-equipment')
        <li>
            <a class="nav-link @if (isset($nl_equipment_class)) {{ $nl_equipment_class }} @endif" href="{{ route('equipment.manage.list') }}">
                <i class="bi bi-laptop bi-2x"></i>
                <span class="nav-text">
                    {{ __('Equipment') }}
                </span>
            </a>
        </li>
        @endcan
        @can('equipment')
        <li>
            <a class="nav-link @if (isset($nl_equipment_class)) {{ $nl_equipment_class }} @endif" href="{{ route('equipment.list') }}">
                <i class="bi bi-laptop bi-2x"></i>
                <span class="nav-text">
                    {{ __('Equipment') }}
                </span>
            </a>
        </li>
        @endcan
        @can('manage-booking')
        <li>
            <a class="nav-link @if (isset($nl_bookings_class)) {{ $nl_bookings_class }} @endif" href="{{ route('booking.manage.list') }}">
                <i class="bi bi-basket2-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Bookings') }}
                </span>
            </a>
        </li>
        @endcan
        @can('booking')
        <li>
            <a class="nav-link @if (isset($nl_bookings_class)) {{ $nl_bookings_class }} @endif" href="{{ route('booking.list') }}">
                <i class="bi bi-basket2-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('My Bookings') }}
                </span>
            </a>
        </li>
        @endcan
        @can('tracking')
        <li>
            <a class="nav-link @if (isset($nl_tracking_class)) {{ $nl_tracking_class }} @endif" href="{{ '' }}">
                <i class="bi bi-grid-3x3-gap-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Tracking') }}
                </span>
            </a>
        </li>
        @endcan
        <li>
            <a class="nav-link @if (isset($nl_hd_class)) {{ $nl_hd_class }} @endif" href="{{ '' }}">
                <i class="bi bi-file-richtext-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Histroical Data') }}
                </span>
            </a>
        </li>
        @can('manage-notification')
        <li>
            <a class="nav-link @if (isset($nl_notification_class)) {{ $nl_notification_class }} @endif" href="{{ '' }}">
                <i class="bi bi-envelope-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Notifications') }}
                </span>
            </a>
        </li>
        @endcan
        @can('notification')
        <li>
            <a class="nav-link @if (isset($nl_notification_class)) {{ $nl_notification_class }} @endif" href="{{ '' }}">
                <i class="bi bi-envelope-fill bi-2x"></i>
                <span class="nav-text">
                    {{ __('Notifications') }}
                </span>
            </a>
        </li>
        @endcan
    </ul>
</nav>