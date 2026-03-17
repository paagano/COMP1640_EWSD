<x-app-layout>

<style>

.table td,
.table th{
    vertical-align: middle;
}

.actions-buttons{
    display:flex;
    justify-content:center;
    gap:6px;
}

.pagination-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:20px;
}

.results-count{
    font-size:14px;
    color:#6c757d;
}

.pagination{
    margin:0;
}

.pagination .page-link{
    color:#0d6efd;
    border:1px solid #dee2e6;
    padding:6px 12px;
    font-size:14px;
}

.pagination .page-item.active .page-link{
    background:#0d6efd;
    border-color:#0d6efd;
    color:#fff;
}

.pagination .page-link:hover{
    background:#e9ecef;
}

.pagination svg{
    width:14px !important;
    height:14px !important;
}

.pagination-container nav > div:first-child > div:first-child{
    display:none !important;
}

</style>


<div class="container py-4">

<h2 class="mb-3 fw-bold">Manage Contributions</h2>
<hr>


{{-- SUCCESS --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
{{ session('success') }}
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif


{{-- ERROR --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
{{ session('error') }}
<button class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif



{{-- SEARCH + FILTER --}}
<form method="GET" id="searchForm" class="mb-3">

<div class="row g-2">

<div class="col-md-4">
<div class="input-group">

<input
type="text"
name="search"
id="searchInput"
class="form-control"
placeholder="Search by title, student, faculty or ID..."
value="{{ request('search') }}"
>

</div>
</div>


<div class="col-md-3">

<select
name="status"
class="form-select"
onchange="document.getElementById('searchForm').submit()"
>

<option value="">All Status</option>

<option value="submitted" {{ request('status')=='submitted'?'selected':'' }}>
Submitted
</option>

<option value="commented" {{ request('status')=='commented'?'selected':'' }}>
Reviewed
</option>

<option value="selected" {{ request('status')=='selected'?'selected':'' }}>
Selected
</option>

<option value="published" {{ request('status')=='published'?'selected':'' }}>
Published
</option>

<option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>
Rejected
</option>

</select>

</div>


<div class="col-md-2">
<button class="btn btn-primary w-100">
Apply
</button>
</div>


@if(request('search') || request('status'))

<div class="col-md-2">

<a
href="{{ route('admin.contributions.index') }}"
class="btn btn-outline-secondary w-100"
>
Clear
</a>

</div>

@endif

</div>

</form>



{{-- CONTRIBUTIONS TABLE --}}
<table class="table table-bordered table-hover align-middle">

<thead class="table-light">

<tr>
<th style="width:6%">ID</th>
<th style="width:18%">Title</th>
<th style="width:26%">Content Summary</th>
<th style="width:12%">Author</th>
<th style="width:14%">Faculty</th>
<th style="width:10%">Status</th>
<th style="width:10%">Created At</th>
<th style="width:14%" class="text-center">Actions</th>
</tr>

</thead>


<tbody>

@forelse($contributions as $contribution)

<tr>

<td>{{ $contribution->id }}</td>

<td>
<strong>{{ $contribution->title }}</strong>
</td>

<td>
{{ \Illuminate\Support\Str::limit($contribution->content_summary,150) }}
</td>

<td>
{{ $contribution->student->name ?? '-' }}
</td>

<td>
{{ $contribution->faculty->name ?? '-' }}
</td>

<td>

@switch($contribution->status)

@case('submitted')
<span class="badge bg-secondary">Submitted</span>
@break

@case('commented')
<span class="badge bg-warning text-dark">Reviewed</span>
@break

@case('selected')
<span class="badge bg-info text-dark">Selected</span>
@break

@case('published')
<span class="badge bg-success">Published</span>
@break

@case('rejected')
<span class="badge bg-danger">Rejected</span>
@break

@default
<span class="badge bg-dark">
{{ ucfirst($contribution->status) }}
</span>

@endswitch

</td>

<td>
{{ $contribution->created_at->format('Y-m-d H:i') }}
</td>

<td>

<div class="actions-buttons">

<a
href="{{ route('admin.contributions.show',$contribution) }}"
class="btn btn-info btn-sm">
View
</a>

{{-- Front-End Validation: Only allow deletion if not selected or published --}}
@if(in_array($contribution->status, ['selected','published']))

<button class="btn btn-secondary btn-sm disabled opacity-75">
Delete
</button>

@else

<form
method="POST"
action="{{ route('admin.contributions.destroy',$contribution) }}"
onsubmit="return confirm('Delete this contribution?')">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm">
Delete
</button>

</form>

@endif

</div>

</td>

</tr>

@empty

<tr>
<td colspan="8" class="text-center">
No contributions found.
</td>
</tr>

@endforelse

</tbody>

</table>



{{-- PAGINATION --}}
@if ($contributions->hasPages())

<div class="pagination-container">

<div class="results-count">
Showing {{ $contributions->firstItem() }}
to {{ $contributions->lastItem() }}
of {{ $contributions->total() }} results
</div>

<div>
{{ $contributions->withQueryString()->links('pagination::bootstrap-5') }}
</div>

</div>

@endif


</div>



<script>

let timer;

document
.getElementById('searchInput')
.addEventListener('keyup',function(){

clearTimeout(timer);

timer=setTimeout(()=>{

document.getElementById('searchForm').submit();

},600);

});

</script>

</x-app-layout>