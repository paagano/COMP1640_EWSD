<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .nav-link {
            padding: 6px 14px;
            border-radius: 6px;
            transition: all .15s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.12);
        }

        .active-nav {
            background: rgba(255,255,255,.22);
            font-weight: 500;
            border-radius: 6px;
        }

        .navbar-user-block {
            line-height: 1.1;
            padding: 6px 12px;
            border-radius: 6px;
        }

        .navbar-user-block small {
            font-size: .75rem;
            opacity: .85;
        }

        .navbar .btn {
            height: 32px;
            display: flex;
            align-items: center;
        }

        /* 🔔 Notification */
        .notification-wrapper {
            position: relative;
            cursor: pointer;
            display: inline-block;
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 4px;
            width: 8px;
            height: 8px;
            background: red;
            border-radius: 50%;
        }

        /* Bell Animation */
        @keyframes bell-ring {
            0% { transform: rotate(0); }
            15% { transform: rotate(15deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(8deg); }
            60% { transform: rotate(-6deg); }
            75% { transform: rotate(4deg); }
            100% { transform: rotate(0); }
        }

        .bell-animate {
            display: inline-block;
            animation: bell-ring 1s ease-in-out infinite;
            transform-origin: top center;
        }
        
        /* Bell icon transition for smooth appearance */
        .notification-wrapper {
            transition: opacity 0.3s ease, transform 0.2s ease;
        }
        
        .notification-wrapper.bell-hidden {
            display: none !important;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold">
                UoG Annual Magazine
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                @php
                    $homeRoute = route('dashboard');

                    if(auth()->user()->hasRole('Admin')){
                        $homeRoute = route('admin.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Marketing Manager')){
                        $homeRoute = route('manager.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Marketing Coordinator')){
                        $homeRoute = route('coordinator.dashboard');
                    }
                    elseif(auth()->user()->hasRole('Student')){
                        $homeRoute = route('student.dashboard');
                    }

                    $lastLogin = session('last_login_at');
                    $isUnread = !auth()->user()->notification_read_at;
                    
                    // Check if bell should be shown based on localStorage preference
                    // This will be handled by JavaScript to persist across pages
                @endphp

                <ul class="navbar-nav ms-auto align-items-center gap-3">
                    <!-- 🔔 BELL - Now conditionally shown via JavaScript after login check -->
                    <li class="nav-item notification-wrapper"
                        id="notificationBell"
                        data-bs-toggle="tooltip"
                        title="Important Notification! Click to Read."
                        style="display: none;">
                        <span class="nav-link text-white">
                            <span id="bellIcon" class="{{ $isUnread ? 'bell-animate' : '' }}">🔔</span>
                        </span>

                        @if($isUnread)
                            <span class="notification-badge" id="notificationBadge"></span>
                        @endif
                    </li>

                    <!-- HOME -->
                    <li class="nav-item">
                        <a href="{{ $homeRoute }}"
                           class="nav-link text-white
                           {{ request()->routeIs('admin.dashboard','manager.dashboard','coordinator.dashboard','student.dashboard') ? 'active-nav' : '' }}">
                            Home
                        </a>
                    </li>

                    <!-- USER -->
                    <li class="nav-item">
                        <a href="{{ route('profile.show') }}" class="text-decoration-none text-white">
                            <div class="d-flex align-items-center gap-2 navbar-user-block
                                 {{ request()->routeIs('profile.show') ? 'active-nav' : '' }}">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                         class="rounded-circle border img-fluid"
                                         width="40"
                                         height="40"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                         style="width:40px; height:40px;">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif

                                <div>
                                    <div class="fw-semibold small">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <small>
                                        {{ $lastLogin ? 'Last login: '.\Carbon\Carbon::parse($lastLogin)->format('d M Y H:i') : 'Welcome 👋' }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <!-- LOGOUT -->
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                            @csrf
                            <button class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- MODAL -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>⚠️ Important Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    This demo system uses <strong>stateless ephemeral cloud storage</strong>.<br><br>
                    Previously uploaded files (profile photos, contribution documents and images) may become unavailable after some time.<br><br>
                    Please re-upload if missing.
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="markNotificationRead">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-fill">
        {{ $slot }}
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <small>© {{ date('Y') }} University of Greenwich</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bell = document.getElementById('notificationBell');
            const bellIcon = document.getElementById('bellIcon');
            const modalElement = document.getElementById('notificationModal');
            const badge = document.getElementById('notificationBadge');
            
            // Only proceed if bell element exists (it always does in this template)
            if (!bell) return;
            
            // Helper function to check if bell should be shown
            function shouldShowBell() {
                // Check localStorage for bell dismissal status
                const bellDismissed = localStorage.getItem('bell_dismissed');
                const dismissalTime = localStorage.getItem('bell_dismissal_time');
                
                // If bell was dismissed less than 90 seconds ago, keep it hidden
                if (bellDismissed === 'true' && dismissalTime) {
                    const timeSinceDismissal = Date.now() - parseInt(dismissalTime);
                    if (timeSinceDismissal < 90000) { // 90 seconds
                        return false;
                    } else {
                        // Clear expired dismissal
                        localStorage.removeItem('bell_dismissed');
                        localStorage.removeItem('bell_dismissal_time');
                    }
                }
                
                // Bell should be shown by default after login
                return true;
            }
            
            // Function to show bell with animation state
            function showBell() {
                if (bell) {
                    bell.style.display = 'inline-block';
                    // Re-initialize tooltip after showing
                    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                        new bootstrap.Tooltip(bell);
                    }
                }
            }
            
            // Function to hide bell
            function hideBell() {
                if (bell) {
                    bell.style.display = 'none';
                }
            }
            
            // Initialize bell visibility based on storage and session
            function initBellVisibility() {
                // Check if bell was dismissed in this session (via sessionStorage for current session only)
                const sessionDismissed = sessionStorage.getItem('bell_session_dismissed');
                if (sessionDismissed === 'true') {
                    hideBell();
                    return;
                }
                
                // Check persistent dismissal
                if (shouldShowBell()) {
                    showBell();
                } else {
                    hideBell();
                }
            }
            
            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                if (el.style.display !== 'none') {
                    new bootstrap.Tooltip(el);
                }
            });
            
            // Only set up bell functionality if modal exists
            let modal = null;
            if (modalElement) {
                modal = new bootstrap.Modal(modalElement);
            }
            
            // Handle bell click - only if bell is visible
            if (bell) {
                bell.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Only open modal if bell is visible
                    if (bell.style.display !== 'none' && modal) {
                        modal.show();
                    }
                });
            }
            
            // Mark notification as read
            const markReadBtn = document.getElementById('markNotificationRead');
            if (markReadBtn) {
                markReadBtn.addEventListener('click', function () {
                    // Send request to mark notification as read
                    fetch("{{ route('notification.read') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        }
                    }).catch(err => console.warn('Notification read request failed', err));
                    
                    // Stop animation
                    if (bellIcon) {
                        bellIcon.classList.remove('bell-animate');
                    }
                    
                    // Remove badge
                    const currentBadge = document.getElementById('notificationBadge');
                    if (currentBadge) {
                        currentBadge.remove();
                    }
                    
                    // Hide modal
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Store dismissal in both sessionStorage and localStorage
                    // sessionStorage ensures bell stays hidden for current session across page navigation
                    sessionStorage.setItem('bell_session_dismissed', 'true');
                    
                    // Store in localStorage with timestamp for persistence across browser sessions
                    localStorage.setItem('bell_dismissed', 'true');
                    localStorage.setItem('bell_dismissal_time', Date.now().toString());
                    
                    // Hide the bell
                    hideBell();
                    
                    // Set timeout to show bell again after 90 seconds (if user stays on page)
                    setTimeout(() => {
                        // Check if we should show bell again after timeout
                        const stillDismissed = sessionStorage.getItem('bell_session_dismissed');
                        if (!stillDismissed || stillDismissed !== 'true') {
                            showBell();
                        } else {
                            // Clear session dismissal to allow bell to reappear on next login
                            sessionStorage.removeItem('bell_session_dismissed');
                        }
                    }, 90000);
                });
            }
            
            // Handle logout - clear bell-related storage to ensure bell appears on next login
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', function() {
                    // Clear all bell-related storage when user logs out
                    localStorage.removeItem('bell_dismissed');
                    localStorage.removeItem('bell_dismissal_time');
                    sessionStorage.removeItem('bell_session_dismissed');
                });
            }
            
            // Initialize bell visibility on page load
            initBellVisibility();
            
            // Re-check bell visibility on page show (in case of back/forward navigation)
            window.addEventListener('pageshow', function(event) {
                // Only reinitialize if page is loaded from cache
                if (event.persisted) {
                    initBellVisibility();
                }
            });
            
            // Store initial bell state in sessionStorage to track across navigation
            // This ensures that if bell was dismissed, it stays dismissed when navigating
            const currentSessionDismissed = sessionStorage.getItem('bell_session_dismissed');
            if (currentSessionDismissed === 'true') {
                hideBell();
            }
            
            // Additional check: If user has already read notification on server side
            // The server-side $isUnread will affect the bell animation and badge
            // But we still need to respect the dismissal state
            @php
                // Pass PHP variable to JavaScript for initial state
                $showBellInitially = !session('bell_dismissed') && !auth()->user()->notification_read_at;
            @endphp
            
            // Override initial visibility based on server-side notification read status
            const isUnreadFromServer = {{ $isUnread ? 'true' : 'false' }};
            const sessionDismissedFlag = sessionStorage.getItem('bell_session_dismissed');
            
            // If notification is already read on server and not dismissed in session, bell should still be visible?
            // Actually if notification is read, bell should not have animation/badge, but should be visible
            if (!isUnreadFromServer && sessionDismissedFlag !== 'true') {
                // Notification already read, but bell can still be shown without animation
                if (bell && bell.style.display === 'none') {
                    showBell();
                }
                if (bellIcon) {
                    bellIcon.classList.remove('bell-animate');
                }
            }
            
            // Ensure bell animation and badge reflect server state
            if (!isUnreadFromServer && bellIcon) {
                bellIcon.classList.remove('bell-animate');
            }
            
            // Handle profile page navigation - bell visibility persists via sessionStorage
            // Add event listener for all navigation links to ensure bell state persists
            document.querySelectorAll('a').forEach(link => {
                // Only handle internal navigation links
                if (link.href && link.href.startsWith(window.location.origin)) {
                    link.addEventListener('click', function() {
                        // Bell state is already stored in sessionStorage, no additional action needed
                        // The pageshow event will restore state on page load
                    });
                }
            });
            
            // Add visibility check for when user returns to tab
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    // Re-check bell visibility when tab becomes visible again
                    const sessionDismissed = sessionStorage.getItem('bell_session_dismissed');
                    if (sessionDismissed !== 'true') {
                        const shouldBeVisible = shouldShowBell();
                        if (shouldBeVisible && bell && bell.style.display === 'none') {
                            showBell();
                        } else if (!shouldBeVisible && bell && bell.style.display !== 'none') {
                            hideBell();
                        }
                    } else {
                        hideBell();
                    }
                }
            });
        });
    </script>
    
    <!-- CSRF Meta Tag -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* To Ensure bell doesn't cause layout shift */
        .notification-wrapper {
            min-width: 44px;
            text-align: center;
        }
        
        /* Smooth transition for bell appearance */
        .notification-wrapper {
            transition: all 0.2s ease;
        }
    </style>

</body>

</html>