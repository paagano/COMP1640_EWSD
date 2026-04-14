<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UoG Annual Magazine System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 (free icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
        }

        .feature-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .1);
        }

        .hero-panel {
            max-width: 420px;
            margin: auto;
        }

        .hero-panel .card {
            border: none;
            border-radius: 10px;
        }

        .hero-panel .panel-item {
            padding: 14px;
            border-bottom: 1px solid #e9ecef;
        }

        .hero-panel .panel-item:last-child {
            border-bottom: none;
        }

        .panel-label {
            font-size: 13px;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: .5px;
        }

        .panel-value {
            font-size: 18px;
            font-weight: 600;
        }

        /* Countdown Timer Badge Styles - Compact Format */
        .countdown-badge {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #1e2a3a;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            white-space: nowrap;
            font-family: 'SF Mono', 'Courier New', monospace;
        }
        
        .countdown-badge i {
            margin-right: 8px;
            font-size: 0.85rem;
        }
        
        .countdown-badge.closed {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        @media (max-width: 768px) {
            .countdown-badge {
                font-size: 0.7rem;
                padding: 5px 10px;
                white-space: normal;
                text-align: center;
                line-height: 1.3;
            }
        }

        /* 🔔 Bell */
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

        .bell-static {
            display: inline-block;
        }

        /* Custom copy button styles inside modal */
        .copy-icon-btn {
            background: transparent;
            border: none;
            color: #0d6efd;
            font-size: 0.9rem;
            padding: 0;
            margin-left: 12px;
            transition: color 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
        }
        .copy-icon-btn:hover {
            color: #0a58ca;
        }
        .copy-icon-btn i {
            pointer-events: none;
            font-size: 0.9rem;
        }
        .copy-feedback {
            font-size: 0.7rem;
            margin-left: 4px;
            color: #198754;
            display: inline-block;
            white-space: nowrap;
        }
        .credential-row td {
            vertical-align: middle;
        }
        /* Email cell styling - unified without subdivision */
        .email-cell-unified {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            flex-wrap: wrap;
        }
        .email-text {
            font-family: monospace;
            word-break: break-all;
            flex: 1;
            background: #f8f9fa;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        .password-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .password-value {
            font-family: monospace;
            font-weight: 600;
            background: #f8f9fa;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        .toast-custom {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1100;
            background: #212529;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
        }
        /* Custom width for demo credentials modal - increased for better readability */
        .demo-modal-wide .modal-dialog {
            max-width: 750px;
            width: 90%;
        }
        @media (min-width: 768px) {
            .demo-modal-wide .modal-dialog {
                max-width: 780px;
            }
        }
        /* Ensure table cells have proper spacing and EQUAL COLUMN WIDTHS */
        .demo-credentials-table {
            width: 100%;
            margin-bottom: 0;
        }
        .demo-credentials-table th,
        .demo-credentials-table td {
            padding: 14px 16px;
            vertical-align: middle;
        }
        /* Force equal width for Role and Email columns - both exactly 50% */
        .demo-credentials-table th:first-child,
        .demo-credentials-table td:first-child {
            width: 50%;
        }
        .demo-credentials-table th:last-child,
        .demo-credentials-table td:last-child {
            width: 50%;
        }
        /* Remove any extra borders or dividers inside email cell */
        .email-cell-unified {
            width: 100%;
        }
        .copy-icon-btn .copy-feedback.d-none {
            display: none;
        }
        .copy-icon-btn .copy-feedback:not(.d-none) {
            display: inline-block;
        }
        /* Responsive adjustments for smaller screens */
        @media (max-width: 576px) {
            .demo-modal-wide .modal-dialog {
                max-width: 95%;
                margin: 0.5rem auto;
            }
            .demo-credentials-table th,
            .demo-credentials-table td {
                padding: 10px 12px;
            }
            .email-cell-unified {
                flex-direction: column;
                align-items: stretch;
            }
            .copy-icon-btn {
                align-self: flex-end;
                margin-left: 0;
                margin-top: 6px;
            }
            .email-text {
                text-align: left;
            }
        }
        /* Ensure role column text is centered nicely on larger screens */
        @media (min-width: 577px) {
            .demo-credentials-table td:first-child {
                text-align: center;
            }
        }
        /* Remove any background or border that might cause visual subdivision */
        .email-text {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .copy-icon-btn:focus-visible {
            outline: 2px solid #0d6efd;
            border-radius: 6px;
        }
        
        /* Bell hover effect */
        .notification-wrapper:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        
        /* Navbar countdown container */
        .countdown-container {
            display: inline-flex;
            align-items: center;
        }
        
        /* Compact countdown text styling */
        .compact-countdown {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* URGENCY ANIMATION (< 24 HOURS) */
        .urgent-btn {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
            animation: pulse-red 1.5s infinite;
        }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        /* FINAL HOUR SHAKE */
        .final-hour-btn {
            animation: shake 0.6s infinite;
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            50% { transform: translateX(2px); }
            75% { transform: translateX(-2px); }
            100% { transform: translateX(0); }
        }

        /* FINAL HOURS BANNER */
        .final-hours-banner {
            background: #dc3545;
            color: white;
            text-align: center;
            font-weight: 700;
            padding: 8px;
            animation: pulse-banner 1.5s infinite;
        }

        @keyframes pulse-banner {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        
    </style>

</head>

<body class="d-flex flex-column min-vh-100">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('home') }}">
                UoG Annual Magazine
            </a>

            <div class="d-flex align-items-center ms-auto">
                <!-- Dynamic Countdown Timer Badge - Compact Format -->
                <div class="countdown-container me-3" id="countdownContainer">
                    <span class="countdown-badge" id="liveCountdownBadge">
                        <i class="fas fa-hourglass-half"></i> 
                        <span id="countdownText" class="compact-countdown">Loading...</span>
                    </span>
                </div>

                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm me-2">
                        Register
                    </a>

                    <!-- Never disappears, stops ringing when clicked -->
                    <div class="notification-wrapper ms-2"
                         id="demoBell"
                         data-bs-toggle="tooltip"
                         title="Click to get demo login credentials">
                        <span class="text-white fs-5">
                            <span id="demoBellIcon" class="bell-animate">🔑</span>
                        </span>
                        <span class="notification-badge" id="demoBadge"></span>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div id="finalHoursBanner" class="final-hours-banner d-none">
        🚨 FINAL HOUR TO SUBMISSION DEADLINE 🚨
    </div>

    <!-- MAIN -->
    <main class="flex-fill">
        <!-- HERO -->
        <section class="hero-section py-5 text-center">
            <div class="container py-4">
                <h1 class="display-5 fw-bold mb-3">
                    University of Greenwich Annual Magazine Portal
                </h1>

                <p class="lead mb-5">
                    A secure, role-based enterprise platform for managing student
                    contributions, faculty review workflows, and annual publication selection.
                </p>

                @if($academicYear)
                    <div class="hero-panel">
                        <div class="card shadow">
                            <div class="panel-item">
                                <div class="panel-label">Academic Year</div>
                                <div class="panel-value">{{ $academicYear->year_name }}</div>
                            </div>

                            <div class="panel-item">
                                <div class="panel-label">Submission Deadline</div>

                                {{-- SHOW ACTUAL SUBMISSION CLOSURE DATE FROM DB --}}
                                <div class="fw-bold text-primary mb-1">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ \Carbon\Carbon::parse($academicYear->submission_closure_date)->format('F j, Y') }}
                                </div>

                                <div class="panel-value" id="deadlinePanelValue">
                                    <span id="heroCountdownText" class="compact-countdown">
                                        @if($submissionClosed)
                                            Submissions Closed
                                        @else
                                            --D --H --M --S Remaining
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="panel-item">
                                @guest
                                    @if(!$submissionClosed)
                                        <a href="{{ route('login') }}" class="btn btn-primary w-100" id="submitBtn">
                                            Submit Your Article Now
                                        </a>
                                    @else
                                        {{-- GREY + DISABLED --}}
                                        <button class="btn btn-secondary w-100" disabled style="cursor: not-allowed;">
                                            Submissions Closed
                                        </button>
                                    @endif
                                @endguest

                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-light w-100">
                                        Go to Dashboard
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- FEATURES -->
        <section class="py-5">
            <div class="container">
                <div class="row text-center g-4">
                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold">Student Contributions</h5>
                                <p class="text-muted">
                                    Secure upload of Word documents and high-resolution
                                    images within controlled submission windows.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold">Faculty Review Workflow</h5>
                                <p class="text-muted">
                                    Coordinators review submissions, provide feedback
                                    within 14 days, and select articles for publication.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card feature-card h-100 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title fw-semibold">Enterprise Reporting</h5>
                                <p class="text-muted">
                                    Real-time statistics, faculty breakdowns, and
                                    exception reporting for governance and oversight.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <small>
                © {{ date('Y') }} University of Greenwich |
                Enterprise Web Software Development Project
            </small>

            <div>
                <button type="button"
                        class="btn btn-link text-white"
                        data-bs-toggle="modal"
                        data-bs-target="#accessibilityModal">
                    Accessibility Statement
                </button>
            </div>
        </div>
    </footer>

    <!-- ACCESSIBILITY MODAL -->
    <div class="modal fade"
         id="accessibilityModal"
         tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Accessibility Statement
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">
                    <div id="accessibilityCarousel"
                         class="carousel slide"
                         data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <h4>Welcome to UoG Annual Magazine Platform!</h4>
                                <p>
                                    At The University of Greenwich, we are committed to ensuring digital accessibility for everyone.
                                </p>
                                <p>
                                    We strive to provide an inclusive online experience that meets the needs of all users, including those with disabilities.
                                </p>
                            </div>
                            <div class="carousel-item">
                                <h4>Our Commitment</h4>
                                <p>
                                    Our website is designed and maintained in accordance with the Web Content Accessibility Guidelines (WCAG) 2.1, Level AA, to ensure compliance with accessibility standards.
                                </p>
                                <p>We continuously work to improve accessibility and welcome feedback from our esteemed users.</p>
                            </div>
                            <div class="carousel-item">
                                <h4>Accessibility Features</h4>
                                <ul>
                                    <li>Keyboard Navigation: Our website can be navigated using a keyboard alone.</li>
                                    <li>Alt Text: All images have descriptive alternative text for screen readers.</li>
                                    <li>Responsive Design: Our website is optimized for use on various devices and screen sizes.</li>
                                    <li>Text Resizing: Users can adjust text size using browser settings.</li>
                                    <li>Contrast: We ensure sufficient color contrast for better readability.</li>
                                </ul>
                            </div>
                            <div class="carousel-item">
                                <h4>Feedback and Contact</h4>
                                <p>We welcome your feedback on the accessibility of our website. If you encounter any barriers or have suggestions for improvement, please don't hesitate to <a href="contact.php">contact us.</a></p>
                                <p>We value your feedback and are committed to making necessary adjustments to improve your browsing experience.</p>
                            </div>
                        </div>
                        <button class="carousel-control-prev"
                                type="button"
                                data-bs-target="#accessibilityCarousel"
                                data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next"
                                type="button"
                                data-bs-target="#accessibilityCarousel"
                                data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- DEMO CREDENTIALS MODAL -->
    <div class="modal fade demo-modal-wide" id="demoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">🔑 Sample Login Credentials</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle demo-credentials-table">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center"><i class="fas fa-user-tie me-1"></i> Role</th>
                                    <th class="text-center"><i class="fas fa-envelope me-1"></i> Email Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="credential-row">
                                    <td class="fw-semibold text-center">Student</td>
                                    <td>
                                        <div class="email-cell-unified">
                                            <code class="email-text" id="email_student">computing.student@uog.ac.uk</code>
                                            <button class="copy-icon-btn" data-copy-value="computing.student@uog.ac.uk" title="Copy email address">
                                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="credential-row">
                                    <td class="fw-semibold text-center">Faculty Marketing Coordinator</td>
                                    <td>
                                        <div class="email-cell-unified">
                                            <code class="email-text" id="email_coordinator">computing.coordinator@uog.ac.uk</code>
                                            <button class="copy-icon-btn" data-copy-value="computing.coordinator@uog.ac.uk" title="Copy email address">
                                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="credential-row">
                                    <td class="fw-semibold text-center">University Marketing Manager</td>
                                    <td>
                                        <div class="email-cell-unified">
                                            <code class="email-text" id="email_manager">manager@uog.ac.uk</code>
                                            <button class="copy-icon-btn" data-copy-value="manager@uog.ac.uk" title="Copy email address">
                                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="credential-row">
                                    <td class="fw-semibold text-center">Guest User</td>
                                    <td>
                                        <div class="email-cell-unified">
                                            <code class="email-text" id="email_guest">computing.guest@uog.ac.uk</code>
                                            <button class="copy-icon-btn" data-copy-value="computing.guest@uog.ac.uk" title="Copy email address">
                                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="credential-row">
                                    <td class="fw-semibold text-center">System Administrator</td>
                                    <td>
                                        <div class="email-cell-unified">
                                            <code class="email-text" id="email_admin">admin@uog.ac.uk</code>
                                            <button class="copy-icon-btn" data-copy-value="admin@uog.ac.uk" title="Copy email address">
                                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PASSWORD SECTION with dedicated copy icon -->
                    <div class="alert alert-info text-center fw-semibold mt-3">
                        <div class="password-wrapper">
                            <span><i class="fas fa-lock me-1"></i> Default Password (All Accounts):</span>
                            <span class="password-value" id="defaultPassword">password123</span>
                            <button class="copy-icon-btn" data-copy-value="password123" title="Copy password">
                                <i class="far fa-copy"></i> <span class="copy-feedback d-none">Copied!</span>
                            </button>
                        </div>
                    </div>

                    <div class="alert alert-danger small text-center mb-0">
                        ⚠️ PLEASE DO NOT DELETE ANY DATA IN THE SYSTEM USING ADMIN RIGHTS
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary w-100" id="closeDemo">
                        Got it <i class="fas fa-check-circle ms-1"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- floating toast for copy feedback -->
    <div id="copyToast" class="toast-custom">
        <i class="fas fa-check-circle me-1"></i> <span id="toastMsg">Copied!</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ============================================
            // ACCURATE COUNTDOWN TIMER - READS FROM DATABASE
            // Reads submission_closure_date from academic_years table
            // NO FALLBACK DEFAULTS - Uses only actual DB data
            // ============================================
            
            // Get the actual deadline from the database via Blade
            // The $deadlineTimestamp variable is set in the controller
            @php
                // Ensure we have a valid deadline from the database
                $deadlineString = null;
                $isClosed = false;
                
                if(isset($academicYear) && $academicYear && isset($academicYear->submission_closure_date)) {
                    $deadlineString = $academicYear->submission_closure_date;
                    $isClosed = isset($submissionClosed) ? $submissionClosed : false;
                }
                
                // Convert to JavaScript timestamp if deadline exists
                $deadlineTimestamp = $deadlineString ? strtotime($deadlineString) : null;
            @endphp
            
            let targetDeadline = null;
            let submissionClosedFlag = @json($isClosed);
            let hasValidDeadline = @json($deadlineTimestamp !== null);
            
            if (hasValidDeadline) {
                // Use the actual database deadline
                targetDeadline = new Date(@json($deadlineTimestamp * 1000));
            } else {
                // No deadline found in database - show error state
                console.error('No submission_closure_date found in academic_years table');
                targetDeadline = null;
            }
            
            // DOM elements
            const countdownTextSpan = document.getElementById('countdownText');
            const heroCountdownSpan = document.getElementById('heroCountdownText');
            const countdownBadge = document.getElementById('liveCountdownBadge');
            
            // Helper: Format number with leading zero (always 2 digits)
            function formatNumber(num) {
                return num.toString().padStart(2, '0');
            }
            
            // Helper: Format the countdown display in compact format: 00D 02H 35M 12S Remaining
            function formatCompactCountdown(days, hours, minutes, seconds) {
                return `${formatNumber(days)}D ${formatNumber(hours)}H ${formatNumber(minutes)}M ${formatNumber(seconds)}S Remaining`;
            }
            
            // Helper: Show deadline missing error
            function showDeadlineMissingError() {
                const errorText = 'Deadline Not Set';
                if (countdownTextSpan) {
                    countdownTextSpan.textContent = errorText;
                }
                if (heroCountdownSpan) {
                    heroCountdownSpan.textContent = errorText;
                }
                if (countdownBadge) {
                    countdownBadge.classList.add('closed');
                    countdownBadge.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Deadline Not Set';
                }
            }
            
            // Helper: Update both displays
            function updateCountdownDisplays(days, hours, minutes, seconds, isClosed) {
                if (isClosed || (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0)) {
                    // Submissions closed
                    const closedText = 'Submissions Closed';
                    if (countdownTextSpan) {
                        countdownTextSpan.textContent = closedText;
                    }
                    if (heroCountdownSpan) {
                        heroCountdownSpan.textContent = closedText;
                    }
                    if (countdownBadge) {
                        countdownBadge.classList.add('closed');
                        countdownBadge.innerHTML = '<i class="fas fa-ban"></i> Submissions Closed';
                    }
                    return true; // indicates closed
                } else {
                    const formatted = formatCompactCountdown(days, hours, minutes, seconds);
                    if (countdownTextSpan) {
                        countdownTextSpan.textContent = formatted;
                    }
                    if (heroCountdownSpan) {
                        heroCountdownSpan.textContent = formatted;
                    }
                    if (countdownBadge && !countdownBadge.classList.contains('closed')) {
                        countdownBadge.innerHTML = `<i class="fas fa-hourglass-half"></i> ${formatted}`;
                    } else if (countdownBadge && countdownBadge.classList.contains('closed')) {
                        // If it was closed but now not, reset style
                        countdownBadge.classList.remove('closed');
                        countdownBadge.innerHTML = `<i class="fas fa-hourglass-half"></i> ${formatted}`;
                    }
                    return false;
                }
            }
            
            // Main countdown tick function
            function tickCountdown() {
                // If no valid deadline from database, show error
                if (!targetDeadline) {
                    showDeadlineMissingError();
                    return;
                }
                
                const now = new Date().getTime();
                const distance = targetDeadline - now;

                const submitBtn = document.getElementById('submitBtn');
                const finalBanner = document.getElementById('finalHoursBanner');

                // CLOSED STATE
                if (distance <= 0 || submissionClosedFlag) {

                    updateCountdownDisplays(0, 0, 0, 0, true);

                    // CLEANUP ALL STATES
                    if (submitBtn) {
                        submitBtn.classList.remove('urgent-btn', 'final-hour-btn');
                    }

                    if (finalBanner) {
                        finalBanner.classList.add('d-none');
                    }

                    return;
                }
                
                // TIME CALCULATION
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const hoursRemaining = distance / (1000 * 60 * 60);

                // TESTING: SIMULATE URGENCY STATES
                // const hoursRemaining = 10; // simulate <24 hours
                // const hoursRemaining = 0.5; // simulate <1 hour

                // URGENCY (<24 HOURS)
                if (submitBtn) {
                    if (hoursRemaining <= 24) {
                        submitBtn.classList.add('urgent-btn');
                    } else {
                        submitBtn.classList.remove('urgent-btn');
                    }

                    // FINAL HOUR (<1 HOUR)
                    if (hoursRemaining <= 1) {
                        submitBtn.classList.add('final-hour-btn');

                        if (finalBanner) {
                            finalBanner.classList.remove('d-none');
                        }
                    } else {
                        submitBtn.classList.remove('final-hour-btn');

                        if (finalBanner) {
                            finalBanner.classList.add('d-none');
                        }
                    }
                }

                updateCountdownDisplays(days, hours, minutes, seconds, false);
            }
            
            // Initial tick only if we have a valid deadline
            if (targetDeadline) {
                tickCountdown();
                // Update every second
                setInterval(tickCountdown, 1000);
            } else {
                showDeadlineMissingError();
            }
            
            // ============================================
            // BELL & DEMO MODAL LOGIC
            // ============================================
            
            const bell = document.getElementById('demoBell');
            const icon = document.getElementById('demoBellIcon');
            const badge = document.getElementById('demoBadge');
            const modalEl = document.getElementById('demoModal');
            const modal = new bootstrap.Modal(modalEl);
            
            // Track if bell has been clicked
            let isBellClicked = localStorage.getItem('demoBellClicked') === 'true';

            // Initialize bell state based on previous click
            function initializeBellState() {
                if (isBellClicked) {
                    if (icon) {
                        icon.classList.remove('bell-animate');
                        icon.classList.add('bell-static');
                    }
                    if (badge) {
                        badge.style.display = 'none';
                    }
                } else {
                    if (icon) {
                        icon.classList.remove('bell-static');
                        icon.classList.add('bell-animate');
                    }
                    if (badge) {
                        badge.style.display = 'block';
                    }
                }
                if (bell) {
                    bell.style.display = 'inline-block';
                }
            }

            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });

            // Bell click handler
            if (bell) {
                bell.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    modal.show();
                    
                    if (!isBellClicked) {
                        isBellClicked = true;
                        localStorage.setItem('demoBellClicked', 'true');
                        if (icon) {
                            icon.classList.remove('bell-animate');
                            icon.classList.add('bell-static');
                        }
                        if (badge) {
                            badge.style.display = 'none';
                        }
                    }
                });
            }

            const closeDemoBtn = document.getElementById('closeDemo');
            if (closeDemoBtn) {
                closeDemoBtn.addEventListener('click', function () {
                    modal.hide();
                });
            }

            // --- COPY FUNCTIONALITY ---
            const toastEl = document.getElementById('copyToast');
            
            function showFloatingToast(message) {
                if (!toastEl) return;
                const msgSpan = document.getElementById('toastMsg');
                if (msgSpan) msgSpan.innerText = message;
                toastEl.style.opacity = '1';
                setTimeout(() => {
                    toastEl.style.opacity = '0';
                }, 1500);
            }

            async function copyToClipboard(text, buttonElement) {
                try {
                    await navigator.clipboard.writeText(text);
                    if (buttonElement) {
                        const feedbackSpan = buttonElement.querySelector('.copy-feedback');
                        if (feedbackSpan) {
                            feedbackSpan.classList.remove('d-none');
                            setTimeout(() => {
                                feedbackSpan.classList.add('d-none');
                            }, 1200);
                        }
                    }
                    const displayText = text.length > 35 ? text.substring(0, 32) + '...' : text;
                    showFloatingToast(`✓ Copied: ${displayText}`);
                } catch (err) {
                    console.warn('Clipboard copy failed', err);
                    fallbackCopyText(text, buttonElement);
                }
            }

            function fallbackCopyText(text, buttonElement) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                const success = document.execCommand('copy');
                document.body.removeChild(textarea);
                if (success && buttonElement) {
                    const feedbackSpan = buttonElement.querySelector('.copy-feedback');
                    if (feedbackSpan) {
                        feedbackSpan.classList.remove('d-none');
                        setTimeout(() => {
                            feedbackSpan.classList.add('d-none');
                        }, 1200);
                    }
                    const displayText = text.length > 35 ? text.substring(0, 32) + '...' : text;
                    showFloatingToast(`✓ Copied: ${displayText}`);
                } else {
                    showFloatingToast('Press Ctrl+C to copy');
                }
            }

            const copyButtons = document.querySelectorAll('.copy-icon-btn');
            
            function handleCopyClick(event) {
                event.preventDefault();
                event.stopPropagation();
                const btn = event.currentTarget;
                const copyValue = btn.getAttribute('data-copy-value');
                if (copyValue && copyValue.trim() !== '') {
                    copyToClipboard(copyValue.trim(), btn);
                } else {
                    showFloatingToast('Unable to copy: value missing');
                }
            }
            
            copyButtons.forEach(btn => {
                btn.removeEventListener('click', handleCopyClick);
                btn.addEventListener('click', handleCopyClick);
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    new bootstrap.Tooltip(btn, { trigger: 'hover', placement: 'top', title: btn.getAttribute('title') || 'Copy to clipboard' });
                }
            });
            
            initializeBellState();
            
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    isBellClicked = localStorage.getItem('demoBellClicked') === 'true';
                    initializeBellState();
                }
            });
            
            const style = document.createElement('style');
            style.textContent = `
                .email-cell-unified {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    width: 100%;
                }
                .email-text {
                    flex: 1;
                    background: #f8f9fa;
                    padding: 6px 12px;
                    border-radius: 6px;
                    font-family: 'SF Mono', 'Courier New', monospace;
                    font-size: 0.85rem;
                    border: 1px solid #e2e8f0;
                    word-break: break-all;
                }
                .copy-icon-btn {
                    background: white;
                    border: 1px solid #dee2e6;
                    border-radius: 6px;
                    padding: 5px 12px;
                    transition: all 0.2s;
                }
                .copy-icon-btn:hover {
                    background: #f8f9fa;
                    border-color: #0d6efd;
                }
                @media (max-width: 576px) {
                    .email-cell-unified {
                        flex-direction: column;
                        align-items: stretch;
                    }
                    .copy-icon-btn {
                        align-self: flex-end;
                        margin-top: 4px;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>

</body>

</html>