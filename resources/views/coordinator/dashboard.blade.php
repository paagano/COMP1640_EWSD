<x-app-layout>
<div class="container py-4">

<h2 class="fw-semibold mb-4">Faculty Marketing Coordinator Dashboard</h2>

<p class="text-muted mb-4">
<strong>{{ Auth::user()->faculty->name ?? 'Not Assigned' }}</strong>
</p>

<p class="text-muted mb-4">
Welcome, {{ auth()->user()->name }}.
</p>

<br>

@if($overdue > 0)
<div class="alert alert-warning">
⚠ {{ $overdue }} submission(s) pending review for more than 14 days.
</div>
@endif


{{-- STATISTICS --}}
<div class="row g-4 mb-4">

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Total Submissions</h6>
<h3 class="fw-bold">{{ $total }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Pending Review</h6>
<h3 class="fw-bold text-warning">{{ $submitted }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Selected</h6>
<h3 class="fw-bold text-success">{{ $selected }}</h3>
</div>
</div>
</div>

<div class="col-md-3">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Rejected</h6>
<h3 class="fw-bold text-danger">{{ $rejected }}</h3>
</div>
</div>
</div>

</div>



{{-- CHARTS --}}
<div class="row g-4 mb-5">

{{-- STATUS PIE --}}
<div class="col-md-6">
<div class="card shadow-sm border-0 h-100">

<div class="card-header fw-semibold">
Contribution Status Breakdown
</div>

<div class="card-body">
<div style="height:300px">
<canvas id="statusChart"></canvas>
</div>
</div>

</div>
</div>


{{-- MONTHLY TREND --}}
<div class="col-md-6">
<div class="card shadow-sm border-0 h-100">

<div class="card-header fw-semibold">
Monthly Submission Trend
</div>

<div class="card-body">
<div style="height:300px">
<canvas id="submissionChart"></canvas>
</div>
</div>

</div>
</div>

</div>



{{-- CONTRIBUTIONS BUTTON --}}
<div class="card shadow-sm border-0 mt-3">

<div class="card-body d-flex justify-content-between align-items-center">

<div>
<h5 class="mb-1">Review Faculty Contributions</h5>

<p class="text-muted mb-0">
View and manage student submissions for your faculty.
</p>

</div>

<a href="{{ route('coordinator.contributions.index') }}"
class="btn btn-primary">

Go to Contributions

</a>

</div>

</div>


</div>



{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

/*
|--------------------------------------------------------------------------
| STATUS BREAKDOWN PIE
|--------------------------------------------------------------------------
*/

new Chart(
document.getElementById('statusChart'),
{
type: 'pie',

data:{
labels:[
'Pending Review',
'Selected',
'Rejected'
],

datasets:[{
data:[
{{ $submitted }},
{{ $selected }},
{{ $rejected }}
],

backgroundColor:[
'#ffc107',
'#198754',
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

new Chart(
document.getElementById('submissionChart'),
{
type:'line',

data:{
labels:{!! json_encode($months ?? []) !!},

datasets:[{
label:'Submissions',

data:{!! json_encode($totals ?? []) !!},

borderColor:'#0d6efd',

backgroundColor:'rgba(13,110,253,0.15)',

fill:true,

tension:0.4
}]
},

options:{
responsive:true,
maintainAspectRatio:false
}

});

</script>

</x-app-layout>