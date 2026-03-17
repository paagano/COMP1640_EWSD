<x-app-layout>
<div class="container py-4">

    <h2 class="fw-semibold mb-4">View Contribution</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- ========================= --}}
            {{-- BASIC DETAILS --}}
            {{-- ========================= --}}
            <table class="table table-bordered align-middle mb-4">
                <tr>
                    <th style="width: 25%" class="bg-light">Title</th>
                    <td>{{ $contribution->title }}</td>
                </tr>

                <tr>
                    <th class="bg-light">Content Summary</th>
                    <td>
                        @if(!empty($contribution->content_summary))
                            <div style="white-space: pre-line;">
                                {{ $contribution->content_summary }}
                            </div>
                        @else
                            <span class="text-muted fst-italic">
                                No content summary provided.
                            </span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="bg-light">Status</th>
                    <td>
                        @if($contribution->status === 'submitted')
                            <span class="badge bg-secondary">Submitted</span>
                        @elseif($contribution->status === 'commented')
                            <span class="badge bg-warning text-dark">Reviewed</span>
                        @elseif($contribution->status === 'selected')
                            <span class="badge bg-success">Selected</span>
                        @elseif($contribution->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="bg-light">Submitted On</th>
                    <td>{{ $contribution->created_at->format('d M Y, h:i A') }}</td>
                </tr>

                <tr>
                    <th class="bg-light">Download Count</th>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $contribution->download_count ?? 0 }} downloads
                        </span>
                    </td>
                </tr>

                <tr>
                    <th class="bg-light">Document</th>
                    <td>
                        <a href="{{ route('student.contributions.download', $contribution->id) }}"
                           class="btn btn-sm btn-outline-primary">
                            Download Document
                        </a>
                    </td>
                </tr>
            </table>


            {{-- ========================= --}}
            {{-- IMAGE PREVIEW SECTION --}}
            {{-- ========================= --}}
            @if($contribution->images && $contribution->images->count() > 0)
                <h5 class="fw-semibold mb-3">Attached Images</h5>

                <div class="row g-3 mb-4">
                    @foreach($contribution->images as $image)
                        <div class="col-md-3">
                            <div class="card shadow-sm">
                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="img-fluid rounded preview-image"
                                     style="height:200px; object-fit:cover; cursor:pointer;"
                                     onclick="openImageModal('{{ asset('storage/'.$image->image_path) }}')">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            {{-- ========================= --}}
            {{-- COORDINATOR REVIEW --}}
            {{-- ========================= --}}
            <h5 class="fw-semibold mb-3">Coordinator Review</h5>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">

                    @if($contribution->status === 'submitted')
                        <div class="alert alert-info mb-0">
                            Your submission is awaiting review by the Marketing Coordinator.
                        </div>
                    @else
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <tr>
                                <th style="width: 25%" class="bg-light">Reviewed By</th>
                                <td>{{ $contribution->reviewed_by ?? 'Marketing Coordinator' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Review Date</th>
                                <td>
                                    {{ $contribution->reviewed_at 
                                        ? \Carbon\Carbon::parse($contribution->reviewed_at)->format('d M Y, h:i A')
                                        : '—'
                                    }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Review Comment</th>
                                <td>
                                    @if(!empty($contribution->review_comment))
                                        <div style="white-space: pre-line;">
                                            {{ $contribution->review_comment }}
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">
                                            No specific comment provided.
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @endif

                </div>
            </div>


            {{-- ========================= --}}
            {{-- SUBMISSION TIMELINE --}}
            {{-- ========================= --}}
            <h5 class="fw-semibold mb-3">Submission Timeline</h5>

            <ul class="list-group mb-4">
                <li class="list-group-item">
                    <strong>Submitted:</strong>
                    {{ $contribution->created_at->format('d M Y, h:i A') }}
                </li>

                @if($contribution->reviewed_at)
                    <li class="list-group-item">
                        <strong>Reviewed:</strong>
                        {{ \Carbon\Carbon::parse($contribution->reviewed_at)->format('d M Y, h:i A') }}
                    </li>
                @endif

                @if($contribution->status === 'selected')
                    <li class="list-group-item text-success">
                        <strong>Selected for Publication</strong>
                    </li>
                @endif

                @if($contribution->status === 'rejected')
                    <li class="list-group-item text-danger">
                        <strong>Rejected</strong>
                    </li>
                @endif
            </ul>


            <div class="text-end">
                <a href="{{ route('student.contributions.index') }}"
                   class="btn btn-secondary">
                    Back
                </a>
            </div>

        </div>
    </div>

</div>


{{-- ========================= --}}
{{-- IMAGE LIGHTBOX MODAL --}}
{{-- ========================= --}}
<div id="imageModal" class="image-modal">
    <span class="close-btn" onclick="closeImageModal()">&times;</span>
    <img id="modalImage" class="modal-content">
</div>


{{-- ========================= --}}
{{-- STYLES --}}
{{-- ========================= --}}
<style>
.image-modal {
    display: none;
    position: fixed;
    z-index: 1050;
    padding-top: 80px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.85);
    text-align: center;
}

.image-modal img {
    max-width: 80%;
    max-height: 80vh;
    border-radius: 8px;
    box-shadow: 0 0 25px rgba(0,0,0,0.6);
    animation: zoomIn 0.3s ease;
}

.close-btn {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

@keyframes zoomIn {
    from { transform: scale(0.7); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}
</style>


{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>
function openImageModal(src) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("modalImage").src = src;
    document.body.style.overflow = "hidden";
}

function closeImageModal() {
    document.getElementById("imageModal").style.display = "none";
    document.body.style.overflow = "auto";
}

window.onclick = function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target === modal) {
        closeImageModal();
    }
}
</script>

</x-app-layout>