<x-app-layout>

<div class="container py-4">

    <h2 class="fw-bold mb-4">
        Faculty Guest Portal
    </h2>

    <p class="text-muted">
        Welcome. You are viewing reports for <strong>{{ $faculty->name }}</strong>
    </p>


    {{-- ========================= --}}
    {{-- STATISTICS --}}
    {{-- ========================= --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body">
                    <h6 class="text-muted">Total Submissions</h6>
                    <h2 class="fw-bold">{{ $totalSubmissions }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body">
                    <h6 class="text-muted">Selected for Publication</h6>
                    <h2 class="fw-bold text-primary">{{ $selectedArticles }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center h-100">
                <div class="card-body">
                    <h6 class="text-muted">Published Articles</h6>
                    <h2 class="fw-bold text-success">{{ $publishedArticles }}</h2>
                </div>
            </div>
        </div>

    </div>



    {{-- ========================= --}}
    {{-- CHARTS --}}
    {{-- ========================= --}}
    <div class="row g-4 mb-4">

        {{-- PIE CHART --}}
        <div class="col-md-6 d-flex">

            <div class="card shadow-sm w-100">

                <div class="card-header fw-semibold">
                    Contribution Status Breakdown
                </div>

                <div class="card-body d-flex justify-content-center align-items-center">

                    <div style="width:320px;height:320px;">
                        <canvas id="statusChart"></canvas>
                    </div>

                </div>

            </div>

        </div>


        {{-- LINE CHART --}}
        <div class="col-md-6 d-flex">

            <div class="card shadow-sm w-100">

                <div class="card-header fw-semibold">
                    Monthly Submission Trend
                </div>

                <div class="card-body">

                    <div style="height:320px;">
                        <canvas id="submissionChart"></canvas>
                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================= --}}
    {{-- COLLAPSIBLE PUBLISHED ARTICLES --}}
    {{-- ========================= --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">

            <span>Recent Published Articles</span>

            <button
                id="toggleButton"
                class="btn btn-sm btn-outline-secondary d-flex align-items-center"
                data-bs-toggle="collapse"
                data-bs-target="#publishedArticlesCollapse"
                aria-expanded="false"
            >

                <span id="toggleText" class="me-1">
                    Open
                </span>

                <span id="chevronIcon" style="transition:transform .3s;">
                    ▼
                </span>

            </button>

        </div>

        <div id="publishedArticlesCollapse" class="collapse">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Published Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($publishedList as $article)

                            <tr>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->student->name ?? '-' }}</td>
                                <td>{{ $article->updated_at->format('d M Y') }}</td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="3" class="text-center py-3">
                                    No published articles yet
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>



    {{-- ========================= --}}
    {{-- DOWNLOAD MAGAZINE --}}
    {{-- ========================= --}}
    <div class="card shadow-sm">

        <div class="card-body text-center">

            <h5 class="fw-semibold mb-3">
                Download Latest Magazine
            </h5>

            <a href="{{ route('guest.download.magazine') }}"
               class="btn btn-primary btn-lg">

                Download Magazine PDF

            </a>

        </div>

    </div>

</div>



{{-- ========================= --}}
{{-- CHART.JS --}}
{{-- ========================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/*
|--------------------------------------------------------------------------
| STATUS PIE CHART
|--------------------------------------------------------------------------
*/

const statusCtx = document.getElementById('statusChart');

new Chart(statusCtx, {
    type: 'pie',
    data: {
        labels: ['Published','Selected','Submitted','Rejected'],
        datasets: [{
            data: [
                {{ $statusChart['published'] ?? 0 }},
                {{ $statusChart['selected'] ?? 0 }},
                {{ $statusChart['submitted'] ?? 0 }},
                {{ $statusChart['rejected'] ?? 0 }}
            ],
            backgroundColor:[
                '#198754',
                '#0d6efd',
                '#ffc107',
                '#dc3545'
            ]
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false
    }
});


/*
|--------------------------------------------------------------------------
| MONTHLY SUBMISSION TREND
|--------------------------------------------------------------------------
*/

const submissionCtx = document.getElementById('submissionChart');

new Chart(submissionCtx, {
    type:'line',
    data:{
        labels: {!! json_encode($months) !!},
        datasets:[{
            label:'Submissions',
            data:{!! json_encode($totals) !!},
            borderColor:'#0d6efd',
            backgroundColor:'rgba(13,110,253,0.2)',
            fill:true,
            tension:0.4
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{
                display:true
            }
        }
    }
});


/*
|--------------------------------------------------------------------------
| COLLAPSE BUTTON TEXT + CHEVRON
|--------------------------------------------------------------------------
*/

const collapseElement = document.getElementById('publishedArticlesCollapse');
const chevronIcon = document.getElementById('chevronIcon');
const toggleText = document.getElementById('toggleText');

collapseElement.addEventListener('show.bs.collapse', function () {

    chevronIcon.style.transform = "rotate(180deg)";
    toggleText.innerText = "Close";

});

collapseElement.addEventListener('hide.bs.collapse', function () {

    chevronIcon.style.transform = "rotate(0deg)";
    toggleText.innerText = "Open";

});

</script>

</x-app-layout>