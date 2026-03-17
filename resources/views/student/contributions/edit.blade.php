<x-app-layout>
<div class="container py-4">

    <h2 class="fw-semibold mb-4">Edit Contribution</h2>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('student.contributions.update', $contribution->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ========================= --}}
                {{-- TITLE --}}
                {{-- ========================= --}}
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $contribution->title) }}"
                           required>
                </div>

                {{-- ========================= --}}
                {{-- CONTENT SUMMARY --}}
                {{-- ========================= --}}
                <div class="mb-3">
                    <label class="form-label">Content Summary</label>
                    <textarea name="content_summary"
                              class="form-control"
                              rows="4"
                              required>{{ old('content_summary', $contribution->content_summary) }}</textarea>
                </div>

                {{-- ========================= --}}
                {{-- WORD DOCUMENT --}}
                {{-- ========================= --}}
                <div class="mb-4">
                    <label class="form-label">Replace Word Document (Optional)</label>
                    <input type="file"
                           name="word_document"
                           class="form-control">
                </div>


                {{-- ========================= --}}
                {{-- EXISTING IMAGES --}}
                {{-- ========================= --}}
                @if($contribution->images && $contribution->images->count() > 0)
                    <h5 class="fw-semibold mb-3">Existing Images</h5>

                    <div class="row g-3 mb-4">
                        @foreach($contribution->images as $image)
                            <div class="col-md-3">
                                <div class="card shadow-sm p-2">

                                    <img src="{{ asset('storage/'.$image->image_path) }}"
                                         class="img-fluid rounded mb-2"
                                         style="height:180px; object-fit:cover;">

                                    {{-- Replace Image --}}
                                    <div class="mb-2">
                                        <label class="form-label small">Replace Image</label>
                                        <input type="file"
                                               name="replace_images[{{ $image->id }}]"
                                               class="form-control form-control-sm">
                                    </div>

                                    {{-- Delete Image --}}
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="delete_images[]"
                                               value="{{ $image->id }}"
                                               id="delete_{{ $image->id }}">
                                        <label class="form-check-label small text-danger"
                                               for="delete_{{ $image->id }}">
                                            Remove Image
                                        </label>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif


                {{-- ========================= --}}
                {{-- ADD NEW IMAGES --}}
                {{-- ========================= --}}
                <div class="mb-4">
                    <label class="form-label">Upload Additional Images (Optional)</label>
                    <input type="file"
                           name="images[]"
                           multiple
                           class="form-control">
                </div>


                {{-- ========================= --}}
                {{-- ACTION BUTTONS --}}
                {{-- ========================= --}}
                <div class="text-end">
                    <a href="{{ route('student.contributions.index') }}"
                       class="btn btn-secondary me-2">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Contribution
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
</x-app-layout>