<x-app-layout>
<div class="container py-4">

    <h2 class="fw-bold mb-4">My Profile</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">

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

            <!-- Right Aligned Button -->
            <div class="text-end">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    Edit Profile
                </a>
            </div>

        </div>
    </div>

</div>
</x-app-layout>