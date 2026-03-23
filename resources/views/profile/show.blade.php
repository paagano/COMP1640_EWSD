<x-app-layout>
<div class="container py-4">

    <h2 class="fw-bold mb-4">My Profile</h2>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

            <div class="row align-items-stretch">

                {{-- =============================== --}}
                {{-- PROFILE PHOTO --}}
                {{-- =============================== --}}
                <div class="col-md-3 mb-3">
                
                    <div class="h-100 d-flex align-items-center justify-content-center bg-light border rounded">

                        @php
                            $photo = $user->profile_photo;

                            if ($photo) {
                                $photoUrl = (strpos($photo, 'http') === 0)
                                    ? $photo
                                    : asset('storage/' . $photo);
                            } else {
                                $photoUrl = null;
                            }
                        @endphp

                        @if($photoUrl)
                            <img src="{{ $photoUrl }}"
                                 class="rounded-circle shadow-sm"
                                 width="120"
                                 height="120"
                                 style="object-fit: cover; border: 2px solid #ddd;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center shadow-sm"
                                 style="width:120px; height:120px; font-size:40px;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif

                    </div>
                
                </div>


                {{-- =============================== --}}
                {{-- PROFILE DETAILS --}}
                {{-- =============================== --}}
                <div class="col-md-9">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-4">

                            <tbody>

                                <tr>
                                    <th style="width: 30%;" class="bg-light">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Role</th>
                                    <td>{{ $user->getRoleNames()->first() }}</td>
                                </tr>

                                <tr>
                                    <th class="bg-light">Last Login</th>
                                    <td>
                                        {{ $user->last_login_at
                                            ? $user->last_login_at->format('d M Y, h:i A')
                                            : 'First Login'
                                        }}
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            Edit Profile
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>
</x-app-layout>