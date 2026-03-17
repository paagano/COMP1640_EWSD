<x-app-layout>

<div class="container py-4">

    {{-- Header + Back Button --}}
    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h2 class="fw-bold mb-2">Reports & Analytics</h2>
            <p class="text-muted mb-0">
                Academic & Exception Reporting Module
            </p>
        </div>

        <a href="{{ route('admin.dashboard') }}"
           class="btn btn-outline-secondary">
            ← Back to Dashboard
        </a>

    </div>


    {{-- Academic Year Filter --}}
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports') }}">

                <div class="row align-items-end">

                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Academic Year
                        </label>

                        <select name="academic_year"
                                class="form-select"
                                onchange="this.form.submit()">

                            @foreach($academicYears as $year)

                                <option value="{{ $year->id }}"
                                    {{ $selectedYearId == $year->id ? 'selected' : '' }}>

                                    {{ $year->year_name ?? $year->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- ====================================================== --}}
    {{-- QUICK REPORTS --}}
    {{-- ====================================================== --}}
    <h4 class="fw-semibold mb-3">Quick Reports</h4>

    <div class="accordion mb-5" id="reportsAccordion">


        {{-- Contributions per Faculty --}}
        <div class="accordion-item mb-3 shadow-sm">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseFaculty">

                    Contributions per Faculty

                </button>

            </h2>

            <div id="collapseFaculty"
                 class="accordion-collapse collapse"
                 data-bs-parent="#reportsAccordion">

                <div class="accordion-body">

                    @role('Admin')

                    <div class="d-flex justify-content-end mb-3">

                        <div class="btn-group btn-group-sm">

                            <a href="{{ route('admin.reports.export',['type'=>'faculty','format'=>'csv','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-secondary">CSV</a>

                            <a href="{{ route('admin.reports.export',['type'=>'faculty','format'=>'excel','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-success">Excel</a>

                            <a href="{{ route('admin.reports.export',['type'=>'faculty','format'=>'pdf','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-danger">PDF</a>

                        </div>

                    </div>

                    @endrole

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Faculty</th>
                                    <th>Total Contributions</th>
                                    <th>% of Total ({{ $totalContributions }})</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($facultyStats as $faculty)

                                    <tr>
                                        <td>{{ $faculty->name }}</td>
                                        <td class="fw-semibold">
                                            {{ $faculty->total_contributions }}
                                        </td>
                                        <td>{{ $faculty->percentage }} %</td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        {{-- Unique Contributors --}}
        <div class="accordion-item mb-3 shadow-sm">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseContributors">

                    Unique Contributors per Faculty

                </button>

            </h2>

            <div id="collapseContributors"
                 class="accordion-collapse collapse"
                 data-bs-parent="#reportsAccordion">

                <div class="accordion-body">

                    @role('Admin')

                    <div class="d-flex justify-content-end mb-3">

                        <div class="btn-group btn-group-sm">

                            <a href="{{ route('admin.reports.export',['type'=>'contributors','format'=>'csv','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-secondary">CSV</a>

                            <a href="{{ route('admin.reports.export',['type'=>'contributors','format'=>'excel','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-success">Excel</a>

                            <a href="{{ route('admin.reports.export',['type'=>'contributors','format'=>'pdf','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-danger">PDF</a>

                        </div>

                    </div>

                    @endrole


                    <div class="table-responsive">

                        <table class="table table-striped align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>Faculty</th>
                                    <th>Unique Contributors</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($contributorsPerFaculty as $faculty)

                                    <tr>
                                        <td>{{ $faculty->name }}</td>
                                        <td class="fw-semibold">
                                            {{ $faculty->unique_contributors }}
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        <br>
        <h4 class="fw-semibold mb-3">Exceptions Reports</h4>


        {{-- Contributions Without Comment --}}
        <div class="accordion-item mb-3 shadow-sm">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed text-warning"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseNoComment">

                    ⚠️ Contributions Without Comment

                </button>

            </h2>

            <div id="collapseNoComment"
                 class="accordion-collapse collapse"
                 data-bs-parent="#reportsAccordion">

                <div class="accordion-body">

                    @role('Admin')

                    <div class="d-flex justify-content-end mb-3">

                        <div class="btn-group btn-group-sm">

                            <a href="{{ route('admin.reports.export',['type'=>'no_comment','format'=>'csv','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-secondary">CSV</a>

                            <a href="{{ route('admin.reports.export',['type'=>'no_comment','format'=>'excel','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-success">Excel</a>

                            <a href="{{ route('admin.reports.export',['type'=>'no_comment','format'=>'pdf','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-danger">PDF</a>

                        </div>

                    </div>

                    @endrole


                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>Title</th>
                                    <th>Student</th>
                                    <th>Faculty</th>
                                    <th>Faculty Coordinator</th>
                                    <th>Date Submitted</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($noCommentContributions as $contribution)

                                    <tr>

                                        <td>{{ $contribution->title }}</td>
                                        <td>{{ $contribution->student->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->faculty->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->faculty->coordinator->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->created_at->format('d M Y') }}</td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="5"
                                            class="text-center text-muted">

                                            No outstanding contributions 🎉

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>



        {{-- SLA Breach --}}
        <div class="accordion-item mb-3 shadow-sm">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed text-danger"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseSLA">

                    🚨 SLA Breach (14+ Days Without Comment)

                </button>

            </h2>

            <div id="collapseSLA"
                 class="accordion-collapse collapse"
                 data-bs-parent="#reportsAccordion">

                <div class="accordion-body">

                    @role('Admin')

                    <div class="d-flex justify-content-end mb-3">

                        <div class="btn-group btn-group-sm">

                            <a href="{{ route('admin.reports.export',['type'=>'sla','format'=>'csv','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-secondary">CSV</a>

                            <a href="{{ route('admin.reports.export',['type'=>'sla','format'=>'excel','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-success">Excel</a>

                            <a href="{{ route('admin.reports.export',['type'=>'sla','format'=>'pdf','academic_year'=>$selectedYearId]) }}"
                               class="btn btn-outline-danger">PDF</a>

                        </div>

                    </div>

                    @endrole

                    <div class="table-responsive">

                        <table class="table table-bordered align-middle">

                            <thead class="table-danger">

                                <tr>
                                    <th>Title</th>
                                    <th>Student</th>
                                    <th>Faculty</th>
                                    <th>Faculty Coordinator</th>
                                    <th>Date Submitted</th>
                                    <th>Days Pending</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($slaBreaches as $contribution)

                                    <tr>

                                        <td>{{ $contribution->title }}</td>
                                        <td>{{ $contribution->student->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->faculty->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->faculty->coordinator->name ?? 'N/A' }}</td>
                                        <td>{{ $contribution->created_at->format('d M Y') }}</td>
                                        <td class="fw-bold text-danger">
                                            {{ \Carbon\Carbon::parse($contribution->created_at)->diffInDays(now()) }} days
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6"
                                            class="text-center text-muted">

                                            No SLA breaches!

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ====================================================== --}}
    {{-- ANALYTICS --}}
    {{-- ====================================================== --}}
    <h4 class="fw-semibold mb-3">Analytics</h4>

    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm mb-4">

        <div>
            <strong>Real-Time Activity</strong>
            <div class="small text-muted">
                Users active in the last 5 minutes
            </div>
        </div>

        <div class="display-6 fw-bold text-primary">
            {{ $activeUsersNow }}
        </div>

    </div>

        {{-- Live Activity Feed --}}
    <div class="accordion mt-4">

        <div class="accordion-item shadow-sm">

            <h2 class="accordion-header">

                <button class="accordion-button collapsed fw-semibold"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseActivityFeed">

                    Live System Activity Feed

                </button>

            </h2>

            <div id="collapseActivityFeed" class="accordion-collapse collapse">

                <div class="accordion-body">

                    <div class="d-flex justify-content-end mb-3">

                        <a href="{{ route('admin.reports.export',['type'=>'activity_feed','format'=>'txt']) }}"
                           class="btn btn-outline-secondary btn-sm">
                           Download TXT
                        </a>

                    </div>

                    <ul class="list-group list-group-flush">

                        @forelse($recentActivity as $activity)

                            <li class="list-group-item d-flex justify-content-between">

                                <div>
                                    <strong>{{ $activity->name }}</strong>
                                    viewed
                                    <span class="text-primary">{{ $activity->page }}</span>
                                </div>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </small>

                            </li>

                        @empty

                            <li class="list-group-item text-center text-muted">
                                No recent activity
                            </li>

                        @endforelse

                    </ul>

                </div>

            </div>

        </div>

    </div>

{{-- Top Pages --}}
<div class="accordion mt-4">

    <div class="accordion-item shadow-sm">

        <h2 class="accordion-header">

            <button class="accordion-button collapsed fw-semibold"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseTopPages">

                Top Pages (Most Visited)

            </button>

        </h2>

        <div id="collapseTopPages" class="accordion-collapse collapse">

            <div class="accordion-body">

                <div class="d-flex justify-content-end mb-3">

                    <div class="btn-group btn-group-sm">

                        <a href="{{ route('admin.reports.export',['type'=>'top_pages','format'=>'csv','academic_year'=>$selectedYearId]) }}"
                           class="btn btn-outline-secondary">CSV</a>

                        <a href="{{ route('admin.reports.export',['type'=>'top_pages','format'=>'excel','academic_year'=>$selectedYearId]) }}"
                           class="btn btn-outline-success">Excel</a>

                        <a href="{{ route('admin.reports.export',['type'=>'top_pages','format'=>'pdf','academic_year'=>$selectedYearId]) }}"
                           class="btn btn-outline-danger">PDF</a>

                    </div>

                </div>

                @php
                    $totalPageVisits = collect($topPages)->sum('total');
                @endphp

                <div class="table-responsive">

                    <table class="table table-striped align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Page</th>
                                <th>Visits</th>
                                <th>% of Total ({{ $totalPageVisits }})</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($topPages as $index => $page)

                                @php
                                    $percentage = $totalPageVisits > 0
                                        ? round(($page->total / $totalPageVisits) * 100, 2)
                                        : 0;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $page->page }}</td>
                                    <td class="fw-semibold">{{ $page->total }}</td>
                                    <td>{{ $percentage }} %</td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No page activity recorded yet
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

    <br>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Most Viewed Pages</div>
                <div class="card-body">
                    <canvas id="pageViewsChart"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Most Active Users</div>
                <div class="card-body">
                    <canvas id="activeUsersChart"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">Browser Usage</div>
                <div class="card-body">
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold">New vs Returning Users</div>
                <div class="card-body">
                    <canvas id="userTypeChart"></canvas>
                </div>
            </div>
        </div>

    </div>

{{-- NEW: Annual Usage Chart --}}
<div class="row mt-4">
    <div class="col-md-12">

        <div class="card shadow-sm border-0">

            <div class="card-header fw-semibold">
                System Activity by Month 
                <span class="text-muted fw-normal">
                    (Academic Year: {{ $academicYears->firstWhere('id', $selectedYearId)->year_name ?? 'N/A' }})
                </span>
            </div>

            <div class="card-body">
                <canvas id="monthlyUsageChart"></canvas>
            </div>

        </div>

    </div>
</div>


</div>


</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(document.getElementById('pageViewsChart'), {
    type: 'bar',
    data: {
        labels: @json($pageViewLabels ?? []),
        datasets: [{
            label: 'Page Views',
            data: @json($pageViewData ?? []),
            backgroundColor: '#3b82f6'
        }]
    }
});

new Chart(document.getElementById('activeUsersChart'), {
    type: 'bar',
    data: {
        labels: @json($activeUserLabels ?? []),
        datasets: [{
            label: 'User Activity',
            data: @json($activeUserData ?? []),
            backgroundColor: '#22c55e'
        }]
    }
});

new Chart(document.getElementById('browserChart'), {
    type: 'doughnut',
    data: {
        labels: @json($browserLabels ?? []),
        datasets: [{
            label: 'Browser Usage',
            data: @json($browserData ?? []),
            backgroundColor: [
                '#3b82f6',
                '#f43f5e',
                '#22c55e',
                '#f59e0b',
                '#8b5cf6'
            ]
        }]
    }
});

new Chart(document.getElementById('userTypeChart'), {
    type: 'pie',
    data: {
        labels: ['New Users','Returning Users'],
        datasets: [{
            label: 'Users',
            data: @json($userTypeData ?? []),
            backgroundColor: [
                '#0ea5e9',
                '#f97316'
            ]
        }]
    }
});

new Chart(document.getElementById('monthlyUsageChart'), {
    type: 'line',
    data: {
        labels: @json($monthlyLabels ?? []),
        datasets: [{
            label: 'System Activity',
            data: @json($monthlyData ?? []),
            borderColor: '#6366f1',
            backgroundColor: 'rgba(99,102,241,0.2)',
            fill: true,
            tension: 0.4
        }]
    }
});

</script>

</x-app-layout>