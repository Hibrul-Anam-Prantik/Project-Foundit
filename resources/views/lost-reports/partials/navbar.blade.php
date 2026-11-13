<header>
    <nav class="navbar">
        <div class="nav-logo">
            <a href="{{ route('lost-reports.index') }}">Found🔎t</a>
        </div>

        <div class="nav-links">
            <a href="{{ route('lost-reports.index') }}" class="nav-btn" style="color: var(--primary-color);">
                <i class="fas fa-list-ul"></i> My Lost Reports
            </a>
            <a href="{{ route('lost-reports.create') }}" class="nav-btn post-lost">
                <i class="fas fa-plus-circle"></i> Report Lost Item
            </a>
        </div>

        <div class="nav-user">
            <div id="user-info" style="font-size:0.9rem; color:#555; display:flex; flex-direction:column; align-items:flex-end;">
                <span class="email">{{ auth()->user()->name }}</span>
                <span class="role">{{ ucfirst(auth()->user()->role) }} &bull; {{ auth()->user()->student_id ?? auth()->user()->email }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="nav-icon" title="Logout" style="background:none; border:none; cursor:pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </nav>
</header>
