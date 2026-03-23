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
                    <th class="bg-light">Student</th>
                    <td>{{ $contribution->student->name }}</td>
                </tr>

                <tr>
                    <th class="bg-light">Faculty</th>
                    <td>{{ $contribution->faculty->name }}</td>
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
                        <a href="{{ route('contributions.download', $contribution->id) }}"
                           target="_blank"
                           class="btn btn-outline-primary btn-sm">
                            Download Document
                        </a>
                    </td>
                </tr>

                <tr>
                    <th class="bg-light">Submitted On</th>
                    <td>{{ $contribution->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            </table>


            {{-- ========================= --}}
            {{-- IMAGE SECTION --}}
            {{-- ========================= --}}
            @if($contribution->images && $contribution->images->count() > 0)
                <h5 class="fw-semibold mb-3">Attached Images</h5>

                <div class="row g-3 mb-4">
                    @foreach($contribution->images as $image)

                        @php
                            $img = $image->image_path;

                            $imgUrl = $img && strpos($img, 'http') === 0
                                ? $img
                                : asset('storage/' . $img);
                        @endphp

                        <div class="col-md-3">
                            <div class="card shadow-sm p-2">

                                <img src="{{ $imgUrl }}"
                                    class="img-fluid rounded"
                                    style="height:200px; object-fit:cover; cursor:pointer;"
                                    title="{{ $image->alt_text }}"
                                    data-bs-toggle="tooltip"

                                    onclick="openImageModal(
                                        '{{ $imgUrl }}',
                                        `{{ addslashes($image->alt_text) }}`
                                    )">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            
            {{-- ========================= --}}
            {{-- COORDINATOR REVIEW --}}
            {{-- ========================= --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-semibold mb-3">Coordinator Review</h5>

                    @if($contribution->status === 'submitted')
                        <div class="alert alert-info mb-0">
                            Awaiting review by Marketing Coordinator.
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
                                            No comment provided.
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
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">

                    <h5 class="fw-semibold mb-3">Submission Timeline</h5>

                    <ul class="list-group">

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

                        @if($contribution->selected_at)
                            <li class="list-group-item text-success">
                                <strong>Selected:</strong>
                                {{ \Carbon\Carbon::parse($contribution->selected_at)->format('d M Y') }}
                            </li>
                        @endif

                        @if($contribution->status === 'rejected')
                            <li class="list-group-item text-danger">
                                <strong>Rejected</strong>
                            </li>
                        @endif

                    </ul>

                </div>
            </div>


            <div class="text-end">
                <a href="{{ route('student.contributions.index') }}"
                   class="btn btn-outline-secondary">
                    ← Back to Contributions List
                </a>
                
                <a href="{{ route('student.dashboard') }}"
                   class="btn btn-outline-secondary">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </div>

</div>


{{-- ========================= --}}
{{-- MODAL --}}
{{-- ========================= --}}
<div id="imageModal" class="image-modal">

    <div class="modal-box">

        <div class="modal-header">
            <h5 class="modal-title">Image Preview</h5>
            <span class="close-btn" onclick="closeImageModal()">&times;</span>
        </div>

        <div class="modal-body text-center">
            <img id="modalImage" class="modal-img">

            <div class="modal-desc mt-3">
                <strong>Image Description:</strong>
                <p id="modalDescription" class="mb-0"></p>
            </div>
        </div>

    </div>

</div>


<style>
.image-modal {
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease;
}

.image-modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-box {
    background: #fff;
    width: 500px;
    max-width: 90%;
    border-radius: 10px;
    overflow: hidden;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.image-modal.show .modal-box {
    transform: scale(1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.modal-body {
    padding: 20px;
}

.modal-img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
}

.close-btn {
    font-size: 22px;
    cursor: pointer;
}
</style>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (el) {
        return new bootstrap.Tooltip(el)
    });
});

function openImageModal(src, description) {
    const modal = document.getElementById("imageModal");

    document.getElementById("modalImage").src = src;
    document.getElementById("modalDescription").innerText = description;

    modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

function closeImageModal() {
    const modal = document.getElementById("imageModal");

    modal.classList.remove("show");
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