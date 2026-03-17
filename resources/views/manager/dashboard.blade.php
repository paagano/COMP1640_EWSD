<x-app-layout>

@php
$user = auth()->user();

/*
|--------------------------------------------------------------------------
| ANALYTICS (Independent of Pagination)
|--------------------------------------------------------------------------
*/

$selectedCount = \App\Models\Contribution::whereIn('status',['selected','published'])->count();

$publishedCount = \App\Models\Contribution::where('status','published')->count();

$pendingCount = $selectedCount - $publishedCount;

@endphp


<div class="container py-4">

<div>
<h2 class="fw-semibold mb-4">
University Marketing Manager Dashboard
</h2>

<small class="text-muted">
Welcome, {{ $user->name }}.
</small>
</div>

<br>


{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
{{ session('success') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
{{ session('error') }}
<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


{{-- ACADEMIC YEAR INFO --}}
@if($academicYear)
<div class="alert alert-info">
<strong>Academic Year:</strong> {{ $academicYear->year_name }} <br>
<strong>Final Closure Date:</strong>
{{ \Carbon\Carbon::parse($academicYear->final_closure_date)->format('d M Y') }}
</div>
@endif



{{-- QUICK ANALYTICS --}}
<div class="row mb-4">

<div class="col-md-4">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Selected for Publication</h6>
<h3 class="fw-bold">
{{ $selectedCount }}
</h3>
</div>
</div>
</div>


<div class="col-md-4">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Published</h6>
<h3 class="fw-bold text-success">
{{ $publishedCount }}
</h3>
</div>
</div>
</div>


<div class="col-md-4">
<div class="card shadow-sm border-0 text-center">
<div class="card-body">
<h6 class="text-muted">Pending Publication</h6>
<h3 class="fw-bold text-warning">
{{ $pendingCount }}
</h3>
</div>
</div>
</div>

</div>



{{-- EXPORT SECTION --}}
@if($finalClosurePassed)

<div class="d-flex justify-content-end gap-2 mb-3">

<a href="{{ route('manager.download.zip') }}" class="btn btn-success">
Download ZIP
</a>

<a href="{{ route('manager.export.csv') }}" class="btn btn-outline-primary">
Export CSV
</a>

<a href="{{ route('manager.export.pdf') }}" class="btn btn-danger">
Export PDF
</a>

</div>

@else

<div class="alert alert-warning">
Export options (ZIP, CSV and PDF) will be available after the final closure date.
</div>

@endif



{{-- CONTRIBUTIONS TABLE --}}
<div class="card shadow-sm border-0">

<div class="card-body p-0">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>
<th style="width: 15%">Title</th>
<th style="width: 25%">Content Summary</th>
<th style="width: 15%">Student</th>
<th style="width: 15%">Faculty</th>
<th style="width: 10%">Selected On</th>
<th style="width: 10%">Published On</th>
<th style="width: 10%" class="text-center">Action</th>
</tr>

</thead>

<tbody>

@forelse($selectedContributions as $contribution)

<tr>

<td>
<strong>{{ $contribution->title }}</strong>
</td>

<td>
@if(!empty($contribution->content_summary))
{{ \Illuminate\Support\Str::limit($contribution->content_summary,120) }}
@else
<span class="text-muted fst-italic">
No summary provided
</span>
@endif
</td>

<td>{{ $contribution->student->name }}</td>

<td>{{ $contribution->faculty->name }}</td>

<td>
{{ \Carbon\Carbon::parse($contribution->selected_at)->format('d M Y') }}
</td>

<td>

@if($contribution->status === 'published' && $contribution->published_at)

{{ \Carbon\Carbon::parse($contribution->published_at)->format('d M Y') }}

@else

—

@endif

</td>


<td>

<div class="d-flex justify-content-center gap-2">

<a href="{{ route('manager.contributions.show',$contribution->id) }}"
class="btn btn-outline-primary d-flex align-items-center justify-content-center"
style="min-width:110px;height:38px;">
View
</a>

@if($contribution->status === 'selected')

<form action="{{ route('manager.contributions.publish',$contribution->id) }}" method="POST">
@csrf

<button type="submit"
class="btn btn-success d-flex align-items-center justify-content-center"
style="min-width:160px;height:38px;"
onclick="return confirm('Mark this contribution as Published?')">

Mark as Published

</button>

</form>

@elseif($contribution->status === 'published')

<button class="btn btn-secondary d-flex align-items-center justify-content-center"
style="min-width:160px;height:38px;"
disabled>

Published

</button>

@endif

</div>

</td>

</tr>

@empty

<tr>
<td colspan="7" class="text-center py-4 text-muted">
No selected or published contributions available.
</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>


{{-- PAGINATION --}}
@if($selectedContributions instanceof \Illuminate\Pagination\LengthAwarePaginator)

<div class="d-flex justify-content-between align-items-center p-3">

<div class="text-muted small">
Showing {{ $selectedContributions->firstItem() }}
to {{ $selectedContributions->lastItem() }}
of {{ $selectedContributions->total() }} results
</div>

<div>
{{ $selectedContributions->links('pagination::bootstrap-5') }}
</div>

</div>

@endif


</div>

</div>

</x-app-layout>