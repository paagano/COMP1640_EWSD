<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UoG Annual Magazine System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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

                @if(isset($daysRemaining) && !$submissionClosed)
                    <span class="badge bg-warning text-dark me-3">
                        {{ $daysRemaining }} Days Remaining
                    </span>
                @endif

                @if(isset($submissionClosed) && $submissionClosed)
                    <span class="badge bg-danger me-3">
                        Submissions Closed
                    </span>
                @endif

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
                @endauth

            </div>

        </div>

    </nav>



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

                            <!-- Academic Year -->
                            <div class="panel-item">

                                <div class="panel-label">
                                    Academic Year
                                </div>

                                <div class="panel-value">
                                    {{ $academicYear->year_name }}
                                </div>

                            </div>



                            <!-- Submission Deadline -->
                            <div class="panel-item">

                                @if($submissionClosed)

                                    <div class="panel-label text-danger">
                                        Submission Status
                                    </div>

                                    <div class="panel-value text-danger">
                                        Submissions Closed
                                    </div>

                                @else

                                    <div class="panel-label">
                                        Submission Deadline
                                    </div>

                                    <div class="panel-value">
                                        {{ $daysRemaining }} Days Remaining
                                    </div>

                                @endif

                            </div>



                            <!-- CTA -->
                            <div class="panel-item">

                                @guest

                                    @if(!$submissionClosed)

                                        <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                            Submit Your Article Now
                                        </a>

                                    @else

                                        <button class="btn btn-secondary w-100" disabled>
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

                                <h5 class="card-title fw-semibold">
                                    Student Contributions
                                </h5>

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

                                <h5 class="card-title fw-semibold">
                                    Faculty Review Workflow
                                </h5>

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

                                <h5 class="card-title fw-semibold">
                                    Enterprise Reporting
                                </h5>

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

                <button
                    type="button"
                    class="btn btn-link text-white"
                    data-bs-toggle="modal"
                    data-bs-target="#accessibilityModal"
                >
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

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div id="accessibilityCarousel"
                         class="carousel slide"
                         data-bs-ride="carousel">

                        <div class="carousel-inner">


                            <!-- Slide 1: WELCOME MESSAGE -->
                            <div class="carousel-item active">

                                <h4>Welcome to UoG Annual Magazine Platform!</h4>

                                <p>
                                    At The University of Greenwich, we are committed to ensuring digital accessibility for everyone.
                                </p>
                                <p>
                                    We strive to provide an inclusive online experience that meets the needs of all users, including those with disabilities.
                                </p>

                            </div>


                            <!-- Slide 2: COMMITMENT -->
                            <div class="carousel-item">

                                <h4>Our Commitment</h4>

                                <p>
                                    Our website is designed and maintained in accordance with the Web Content Accessibility Guidelines (WCAG) 2.1, Level AA, to ensure compliance with accessibility standards.
                                    <p>We continuously work to improve accessibility and welcome feedback from our esteemed users.</p>
                                </p>

                            </div>


                            <!-- Slide 3: ACCESSIBILITY FEATURES -->
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


                            <!-- Slide 4: FEEDBACK AND CONTACT -->
                            <div class="carousel-item">

                                <h4>Feedback and Contact</h4>

                                <p>We welcome your feedback on the accessibility of our website. If you encounter any barriers or have suggestions for improvement, please don’t hesitate to <a href="contact.php">contact us.</a></p>
                                <p>We value your feedback and are committed to making necessary adjustments to improve your browsing experience.</p>

                            </div>

                        </div>

                        <!-- Carousel Controls -->

                        <button
                            class="carousel-control-prev"
                            type="button"
                            data-bs-target="#accessibilityCarousel"
                            data-bs-slide="prev">

                            <span class="carousel-control-prev-icon"></span>
                        </button>


                        <button
                            class="carousel-control-next"
                            type="button"
                            data-bs-target="#accessibilityCarousel"
                            data-bs-slide="next">

                            <span class="carousel-control-next-icon"></span>
                        </button>

                    </div>

                </div>


                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Close
                    </button>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>