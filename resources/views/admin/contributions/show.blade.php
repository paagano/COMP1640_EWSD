<x-app-layout>
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold mb-0">Contribution Details</h2>

        <a href="{{ route('admin.contributions.index') }}"
           class="btn btn-outline-secondary">
            ← Back to Contributions
        </a>
    </div>

    {{-- ========================= --}}
    {{-- MAIN DETAILS CARD --}}
    {{-- ========================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            @php
                $doc = $contribution->word_document_path;

                $docUrl = $doc && strpos($doc, 'http') === 0
                    ? $doc
                    : asset('storage/' . $doc);

                $isLocal = str_contains($docUrl, '127.0.0.1') || str_contains($docUrl, 'localhost');
            @endphp

            <table class="table table-bordered align-middle mb-0">
                <tr>
                    <th style="width: 25%" class="bg-light">Title</th>
                    <td><strong>{{ $contribution->title }}</strong></td>
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
                                No summary provided.
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
                        @if($contribution->status === 'selected')
                            <span class="badge bg-success">Selected</span>
                        @elseif($contribution->status === 'commented')
                            <span class="badge bg-warning text-dark">Reviewed</span>
                        @elseif($contribution->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @else
                            <span class="badge bg-secondary text-capitalize">
                                {{ $contribution->status }}
                            </span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="bg-light">Selected On</th>
                    <td>
                        {{ $contribution->selected_at 
                            ? \Carbon\Carbon::parse($contribution->selected_at)->format('d M Y')
                            : '—'
                        }}
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

                        {{-- READ BUTTON --}}
                        <button class="btn btn-outline-success btn-sm ms-2"
                                data-bs-toggle="modal"
                                data-bs-target="#readDocumentModal">
                            Read Online
                        </button>
                    </td>
                </tr>
            </table>

        </div>
    </div>


    {{-- ========================= --}}
    {{-- IMAGE SECTION --}}
    {{-- ========================= --}}
    @if($contribution->images && $contribution->images->count() > 0)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">Attached Images</h5>

                <div class="row g-3">
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

            </div>
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

</div>


{{-- ========================= --}}
{{-- READ DOCUMENT MODAL --}}
{{-- ========================= --}}
<div class="modal fade" id="readDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">📄 Read Document</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">

                @if($isLocal)
                    <div class="p-4 text-center">
                        <p class="text-muted">
                            Preview not available on local environment.
                        </p>

                        <a href="{{ $docUrl }}" target="_blank" class="btn btn-primary">
                            Download Document
                        </a>
                    </div>
                @else
                    <iframe
                        src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($docUrl) }}"
                        width="100%"
                        height="600px"
                        frameborder="0">
                    </iframe>
                @endif

            </div>

        </div>
    </div>
</div>


{{-- ========================= --}}
{{-- IMAGE MODAL --}}
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
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
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