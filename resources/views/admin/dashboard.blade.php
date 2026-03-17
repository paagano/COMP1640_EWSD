<x-app-layout>
<div class="container py-4" id="dashboardContent">

    {{-- =============================== --}}
    {{-- Header + Export Buttons --}}
    {{-- =============================== --}}
    <h2 class="fw-bold mb-2">Admin Dashboard</h2>

    <p class="text-muted mb-4">
        Welcome, {{ auth()->user()->name }}.
    </p>

    {{-- =============================== --}}
    {{-- Academic Year Filter + Reports + Export --}}
    {{-- =============================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <form method="GET" action="{{ route('admin.dashboard') }}">
            <select name="academic_year" class="form-select" onchange="this.form.submit()">
                <option value="">Filter by Academic Year</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}"
                        {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                        {{ $year->year_name ?? $year->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="d-flex gap-2">

            @role('Admin')
            <a href="{{ route('admin.reports') }}" 
               class="btn btn-outline-primary position-relative">
                View Reports
                @if($overdueCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $overdueCount }}
                    </span>
                @endif
            </a>
            @endrole

            <button class="btn btn-dark" onclick="exportDashboardPDF()">
                Export Dashboard as PDF
            </button>

        </div>
    </div>

    {{-- ========================= --}}
    {{-- Global Statistics Cards --}}
    {{-- ========================= --}}
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Contributions</h6>
                    <h3 class="fw-bold">{{ $totalContributions }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Students</h6>
                    <h3 class="fw-bold">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Faculties</h6>
                    <h3 class="fw-bold">{{ $totalFaculties }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center bg-warning bg-opacity-10">
                <div class="card-body">
                    <h6 class="text-muted">SLA Overdue (14+ days)</h6>
                    <h3 class="fw-bold text-danger">{{ $overdueCount }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================= --}}
    {{-- Faculty Performance Ranking (Collapsible) --}}
    {{-- ========================================= --}}
    <div class="accordion mb-5" id="rankingAccordion">
        <div class="accordion-item shadow-sm border-0">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#rankingCollapse">
                    Faculty Performance Ranking (by Selected Articles)
                </button>
            </h2>

            <div id="rankingCollapse"
                 class="accordion-collapse collapse">
                <div class="accordion-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Faculty</th>
                                    <th>Selected Articles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($facultyRanking as $index => $faculty)
                                    <tr>
                                        <td>#{{ $index + 1 }}</td>
                                        <td>{{ $faculty->name }}</td>
                                        <td class="fw-bold text-success">
                                            {{ $faculty->selected_count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =============================== --}}
    {{-- Charts Section --}}
    {{-- =============================== --}}
    <div class="row g-4 mt-3">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 chart-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Monthly Submission Trend</h6>
                    <div class="chart-container">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 chart-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Contributions per Faculty</h6>
                    <div class="chart-container">
                        <canvas id="facultyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 chart-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Contribution Status Distribution</h6>
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 chart-card">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3">Top 5 Faculties (Selected Articles)</h6>
                    <div class="chart-container">
                        <canvas id="topFacultyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- External Libraries --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
// ================= Charts =================
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($trendMonths),
        datasets: [{
            label: 'Submissions',
            data: @json($trendCounts),
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13,110,253,0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('facultyChart'), {
    type: 'bar',
    data: {
        labels: @json($facultyNames),
        datasets: [{
            label: 'Total Contributions',
            data: @json($facultyTotals),
            backgroundColor: 'rgba(54,162,235,0.7)',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: ['Submitted','Reviewed','Selected','Rejected'],
        datasets: [{
            data: [
                {{ $statusCounts['submitted'] }},
                {{ $statusCounts['commented'] }},
                {{ $statusCounts['selected'] }},
                {{ $statusCounts['rejected'] }}
            ],
            backgroundColor: ['#6c757d','#ffc107','#198754','#dc3545']
        }]
    },
    options: { responsive: true, maintainAspectRatio: false }
});

new Chart(document.getElementById('topFacultyChart'), {
    type: 'bar',
    data: {
        labels: @json($facultyRanking->take(5)->pluck('name')),
        datasets: [{
            label: 'Selected Articles',
            data: @json($facultyRanking->take(5)->pluck('selected_count')),
            backgroundColor: '#198754',
            borderRadius: 6
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true } }
    }
});

// ================= PDF Export =================
async function exportDashboardPDF() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    const dashboard = document.getElementById('dashboardContent');

    const canvas = await html2canvas(dashboard);
    const imgData = canvas.toDataURL('image/png');

    const imgWidth = 210;
    const pageHeight = 295;
    const imgHeight = canvas.height * imgWidth / canvas.width;

    let heightLeft = imgHeight;
    let position = 0;

    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
    heightLeft -= pageHeight;

    while (heightLeft > 0) {
        position = heightLeft - imgHeight;
        pdf.addPage();
        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;
    }

    pdf.save('Admin-Dashboard-Report.pdf');
}
</script>

<style>
.chart-card { height: 380px; }
.chart-container { position: relative; height: 300px; }
</style>

</x-app-layout>