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
            </table>


            {{-- ========================= --}}
            {{-- IMAGE SECTION --}}
            {{-- ========================= --}}
            @if($contribution->images && $contribution->images->count() > 0)
                <h5 class="fw-semibold mb-3">Attached Images</h5>

                <div class="row g-3 mb-4">
                    @foreach($contribution->images as $image)
                        <div class="col-md-3">
                            <div class="card shadow-sm p-2">

                                <img src="{{ asset('storage/'.$image->image_path) }}"
                                     class="img-fluid rounded"
                                     style="height:200px; object-fit:cover; cursor:pointer;"
                                     
                                     title="{{ $image->alt_text }}"
                                     data-bs-toggle="tooltip"

                                     onclick="openImageModal(
                                        '{{ asset('storage/'.$image->image_path) }}',
                                        `{{ addslashes($image->alt_text) }}`
                                     )">
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


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


{{-- ========================= --}}
{{-- STYLES --}}
{{-- ========================= --}}
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

    /* HIDDEN BY DEFAULT */
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

/* HEADER */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.modal-title {
    margin: 0;
    font-weight: 600;
}

/* BODY */
.modal-body {
    padding: 20px;
}

.modal-img {
    width: 100%;
    max-height: 300px;
    object-fit: contain;
}

/* DESCRIPTION */
.modal-desc {
    text-align: left;
    font-size: 14px;
    color: #444;
}

/* CLOSE */
.close-btn {
    font-size: 22px;
    cursor: pointer;
}
</style>


{{-- ========================= --}}
{{-- SCRIPT --}}
{{-- ========================= --}}
<script>

// TOOLTIP INIT
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

// CLOSE ON OUTSIDE CLICK
window.onclick = function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target === modal) {
        closeImageModal();
    }
}

</script>

</x-app-layout>