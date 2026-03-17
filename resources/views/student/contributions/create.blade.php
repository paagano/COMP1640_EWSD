<x-app-layout>
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-semibold mb-1">New Contribution</h2>
        <small class="text-muted">
            Submit your article for review and publication consideration.
        </small>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST"
                  action="{{ route('student.contributions.store') }}"
                  enctype="multipart/form-data">

                @csrf

                {{-- Title --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Article Title</label>
                    <input type="text"
                           name="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Summary --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Content Summary</label>
                    <textarea name="content_summary"
                              rows="4"
                              class="form-control @error('content_summary') is-invalid @enderror"
                              required>{{ old('content_summary') }}</textarea>
                    @error('content_summary')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Word Document --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Word Document
                    </label>

                    <div class="text-muted small mb-1">
                        Allowed file types: .doc / .docx
                    </div>

                    <input type="file"
                           name="word_document"
                           class="form-control @error('word_document') is-invalid @enderror"
                           required>

                    @error('word_document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Images --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Upload Supporting Images (Optional)
                    </label>

                    <div class="text-muted small mb-1">
                        Allowed image formats: .jpg / .jpeg / .png
                    </div>

                    <input type="file"
                           name="images[]"
                           multiple
                           class="form-control @error('images.*') is-invalid @enderror">

                    @error('images.*')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Terms --}}
                <div class="form-check mb-4">
                    <input type="checkbox"
                           name="agreed_terms"
                           class="form-check-input @error('agreed_terms') is-invalid @enderror"
                           required>

                    <label class="form-check-label">
                        I confirm that I agree to the
                        <a href="#"
                           data-bs-toggle="modal"
                           data-bs-target="#termsModal">
                           Terms and Conditions
                        </a>
                        and that this work is my original submission.
                    </label>

                    @error('agreed_terms')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('student.contributions.index') }}"
                       class="btn btn-outline-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-success px-4">
                        Submit Contribution
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>


<!-- Terms and Conditions Modal: This modal is included on the contribution submission page to ensure that users agree to the terms and conditions related to their submissions before they can proceed with submitting their articles for review and publication consideration. -->

@include('components.terms-modal')

</x-app-layout>