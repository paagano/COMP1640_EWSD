<x-app-layout>
<div class="container py-4">

    @php
        $user = auth()->user();

        $academicYear = \App\Models\AcademicYear::latest()->first();

        $total = $user->contributions()->count();
        $submitted = $user->contributions()->where('status','submitted')->count();
        $commented = $user->contributions()->where('status','commented')->count();
        $selected = $user->contributions()->where('status','selected')->count();
        $rejected = $user->contributions()->where('status','rejected')->count();

        $latest = $user->contributions()->latest()->first();

        $daysRemaining = null;
        $submissionClosed = false;

        if ($academicYear) {
            $today = \Carbon\Carbon::today();
            $closure = \Carbon\Carbon::parse($academicYear->submission_closure_date);

            if ($today->gt($closure)) {
                $submissionClosed = true;
            } else {
                $daysRemaining = $today->diffInDays($closure);
            }
        }
    @endphp

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-semibold mb-1">Student Dashboard</h2>

        <p class="text-muted mb-4">
            <strong>
                {{ Auth::user()->faculty->name ?? 'Not Assigned' }}
            </strong>
        </p>

        <small class="text-muted">
            Welcome, {{ $user->name }}.
        </small>
    </div>

    <!-- Deadline Banner -->
    @if($academicYear)
        <div class="alert {{ $submissionClosed ? 'alert-danger' : 'alert-info' }} shadow-sm">
            <strong>Academic Year {{ $academicYear->year_name }}</strong> —
            @if($submissionClosed)
                Submission period is now closed.
            @else
                {{ $daysRemaining }} days remaining before submission deadline.
            @endif
        </div>
    @endif

    <!-- Action Cards -->
    <div class="row g-4 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3">📂 My Contributions</h5>
                    <p class="text-muted">
                        View all your submissions and track their review status.
                    </p>
                    <a href="{{ route('student.contributions.index') }}"
                       class="btn btn-outline-primary">
                        View Contributions
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-semibold mb-3">✍️ Submit New Article</h5>
                    <p class="text-muted">
                        Upload a new article including Word documents and images.
                    </p>

                    <div class="mt-auto d-flex justify-content-between align-items-center">

                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#termsModal">
                            Read Submission Terms & Conditions
                        </button>

                        <a href="{{ route('student.contributions.create') }}"
                           class="btn btn-success"
                           {{ $submissionClosed ? 'disabled' : '' }}>
                            + New Submission
                        </a>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Submission Overview -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-4">Submission Overview</h6>

            <div class="row text-center">
                <div class="col-md-3">
                    <h5 class="fw-bold">{{ $total }}</h5>
                    <small class="text-muted">Total</small>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold text-warning">{{ $submitted }}</h5>
                    <small class="text-muted">Under Review</small>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold text-success">{{ $selected }}</h5>
                    <small class="text-muted">Selected</small>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold text-danger">{{ $rejected }}</h5>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>

            @if($total > 0)
                @php
                    $progress = round(($selected / $total) * 100);
                @endphp

                <div class="mt-4">
                    <small class="text-muted">Publication Success Rate</small>
                    <div class="progress mt-2" style="height: 10px;">
                        <div class="progress-bar bg-success"
                             role="progressbar"
                             style="width: {{ $progress }}%">
                        </div>
                    </div>
                    <small class="text-muted">{{ $progress }}% selected</small>
                </div>
            @endif

        </div>
    </div>

    <!-- Latest Submission -->
    @if($latest)
        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-column">

                <h6 class="fw-semibold mb-3">Latest Submission</h6>

                <h5>{{ $latest->title }}</h5>

                <p class="text-muted">
                    {{ \Illuminate\Support\Str::limit($latest->content_summary, 150) }}
                </p>

                <!-- STATUS + BUTTON ROW -->
                <div class="mt-auto d-flex justify-content-between align-items-center">

                    <span class="badge bg-secondary">
                        Status: {{ ucfirst($latest->status) }}
                    </span>

                    <a href="{{ route('student.contributions.show', $latest->id) }}"
                       class="btn btn-outline-primary btn-sm">
                        View Details
                    </a>

                </div>

            </div>
        </div>
    @endif

</div>

<!-- Terms and Conditions Modal -->
@include('components.terms-modal')

</x-app-layout>