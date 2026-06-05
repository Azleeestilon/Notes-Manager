<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings - Notes Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark bg-opacity-95 text-light" style="min-height: 100vh;">

    @include('navbar')

    <div class="container mt-5 pt-3">
        
        {{-- DISPLAY ALERTS --}}
        @if(session('success'))
            <div class="alert alert-success bg-success bg-opacity-10 border-success text-success rounded-3 small p-3 mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger rounded-3 small p-3 mb-4" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <h2 class="fw-bold mb-1">Account & <span class="text-success text-opacity-75">Settings</span></h2>
            <p class="text-small">Manage your profile details and app configurations.</p>
        </div>

        <div class="row">
            
            {{-- USER DETAILS CARD --}}
            <div class="col-md-5 mb-4">
                <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4 shadow-lg text-light h-100">
                    <div class="card-body d-flex flex-column justify-content-between h-100">
                        <div>
                            <div class="text-center mb-4">
                                {{-- PROFILE PICTURE DISPLAY --}}
                                <div class="d-inline-block position-relative mb-3">
                                    @if(auth()->user()->profile_image)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" 
                                             alt="Profile Picture" 
                                             class="rounded-circle border border-success border-opacity-50 object-fit-cover" 
                                             style="width: 90px; height: 90px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-success bg-opacity-25 text-success border border-success border-opacity-50 rounded-circle m-auto" 
                                             style="width: 90px; height: 90px; font-size: 2.2rem;">
                                            👤
                                        </div>
                                    @endif
                                </div>
                                <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-1 rounded-3 small">Active Workspace</span>
                            </div>

                            <hr class="border-success border-opacity-25 my-4">

                            {{-- EDIT PROFILE FORM (WITH FILE UPLOAD) --}}
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Account Name</label>
                                    <input type="text" id="name" name="name" class="form-control bg-dark border border-success border-opacity-25 text-white p-3 rounded-3" value="{{ auth()->user()->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control bg-dark border border-success border-opacity-25 text-white p-3 rounded-3" value="{{ auth()->user()->email }}" required>
                                </div>

                                {{-- PROFILE IMAGE INPUT SECTION --}}
                                <div class="mb-4">
                                    <label for="profile_image" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                                        @if(auth()->user()->profile_image)
                                            Change Profile Picture
                                        @else
                                            Upload Profile Picture
                                        @endif
                                    </label>
                                    <input type="file" id="profile_image" name="profile_image" class="form-control bg-dark border border-success border-opacity-25 text-white rounded-3 small" accept="image/*">
                                    <div class="opacity-50 tools-tip mt-1" style="font-size: 0.75rem;">Accepted formats: JPG, PNG, JPEG. Max 2MB.</div>
                                </div>

                                <button type="submit" class="btn btn-success px-4 py-2 fw-semibold rounded-3 w-100 mb-2">
                                    Save Changes
                                </button>
                            </form>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-success border-opacity-50 px-4 py-2 fw-semibold rounded-3 w-100">
                                ← Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- WORKSPACE PREFERENCES CARD --}}
            <div class="col-md-7 mb-4">
                <div class="card bg-secondary bg-opacity-10 border border-success border-opacity-25 rounded-4 p-4 shadow-lg text-light h-100">
                    <div class="card-body">
                        <h4 class="fw-bold mb-1">Workspace Preferences</h4>
                        <p class="text-small mb-4">Tweak how your dashboard behaves.</p>

                        <hr class="border-success border-opacity-25 my-4">

                        {{-- ALERTS PARA SA AJAX SETTINGS UPDATE --}}
                        <div id="settingsAlert" class="alert alert-success bg-success bg-opacity-10 border-success text-success rounded-3 small p-2 mb-3 d-none" role="alert">
                            Preferences updated successfully!
                        </div>

                        <h6 class="fw-bold text-success text-opacity-75 small mb-3 text-uppercase" style="letter-spacing: 0.5px;">🔔 Notifications</h6>
                        
                        {{-- TOGGLE FOR DESKTOP NOTIFICATIONS --}}
                        <div class="form-check form-switch bg-dark border border-success border-opacity-10 rounded-3 p-3 ps-5 mb-3">
                            <input class="form-check-input setting-toggle" type="checkbox" role="switch" id="notifShow" 
                                   {{ auth()->user()->desktop_notifications ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium ms-2" for="notifShow">Show desktop notifications</label>
                            <div class="text-muted small ms-2 mt-1">Get alerts when notes are modified or shared.</div>
                        </div>

                        {{-- TOGGLE FOR ALERT SOUNDS --}}
                        <div class="form-check form-switch bg-dark border border-success border-opacity-10 rounded-3 p-3 ps-5 mb-4">
                            <input class="form-check-input setting-toggle" type="checkbox" role="switch" id="notifSound"
                                   {{ auth()->user()->alert_sounds ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium ms-2" for="notifSound">Enable alert sounds</label>
                        </div>

                        <h6 class="fw-bold text-success text-opacity-75 small mb-3 text-uppercase" style="letter-spacing: 0.5px;">⚙️ Maintenance Utilities</h6>
                        
                        {{-- EMPTY TRASH UTILITY BUTTON --}}
                        <div class="bg-dark border border-success border-opacity-10 rounded-3 p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="fw-medium text-white">Empty Trash Bin</div>
                                    <div class="text-small mt-1">Permanently erase all currently trashed items inside your workspace.</div>
                                </div>
                                <form action="{{ route('trash.empty') }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-2 fw-semibold rounded-3">
                                        🗑️ Force Clear Trash
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- AUTO-DELETE INTERVAL SELECTOR --}}
                        <div class="bg-dark border border-success border-opacity-10 rounded-3 p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="fw-medium text-white">Auto-Delete Interval</div>
                                    <div class="text-muted small mt-1">Choose when trash items expire automatically.</div>
                                </div>
                                <select id="deleteInterval" class="form-select btn-sm bg-secondary bg-opacity-10 text-white border-secondary border-opacity-50 rounded-3 w-auto small" style="min-width: 140px;">
                                    <option value="7" class="bg-dark" {{ auth()->user()->auto_delete_interval == 7 ? 'selected' : '' }}>After 7 days</option>
                                    <option value="30" class="bg-dark" {{ auth()->user()->auto_delete_interval == 30 ? 'selected' : '' }}>After 30 days</option>
                                    <option value="0" class="bg-dark" {{ auth()->user()->auto_delete_interval == 0 ? 'selected' : '' }}>Never delete</option>
                                </select>
                            </div>
                        </div>

                        <hr class="border-success border-opacity-25 my-4">

                        {{-- CHANGE PASSWORD SECTION --}}
                        <h6 class="fw-bold text-success text-opacity-75 small mb-3 text-uppercase" style="letter-spacing: 0.5px;">🔒 Security & Password</h6>
                        <form action="{{ route('profile.password') }}" method="POST" class="bg-dark border border-success border-opacity-10 rounded-3 p-4 mb-4">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Current Password</label>
                                <input type="password" id="current_password" name="current_password" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-2.5 rounded-3" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">New Password</label>
                                <input type="password" id="new_password" name="new_password" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-2.5 rounded-3" required>
                            </div>

                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control bg-secondary bg-opacity-10 border border-secondary border-opacity-25 text-white p-2.5 rounded-3" required>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-sm btn-success px-4 py-2 fw-semibold rounded-3">
                                    Update Password
                                </button>
                            </div>
                        </form>

                        <hr class="border-success border-opacity-25 my-4">
                        
                        {{-- LOGOUT UTILITY --}}
                        <div class="d-flex justify-content-end">
                            <form action="{{ route('logout') }}" method="POST" class="w-auto">
                                @csrf
                                <button type="submit" class="btn btn-danger bg-gradient px-4 py-2 fw-semibold rounded-3">
                                    Sign Out Account
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AUTOMATIC AJAX PREFERENCES UPDATER SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const notifShow = document.getElementById('notifShow');
            const notifSound = document.getElementById('notifSound');
            const deleteInterval = document.getElementById('deleteInterval');
            const alertBox = document.getElementById('settingsAlert');

            function savePreferences() {
                // Pag-compile ng data mula sa UI form states
                const data = {
                    desktop_notifications: notifShow.checked ? 1 : 0,
                    alert_sounds: notifSound.checked ? 1 : 0,
                    auto_delete_interval: deleteInterval.value,
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT'
                };

                // AJAX Request gamit ang Fetch API
                fetch('{{ route("profile.settings.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if(result.status === 'success') {
                        // Magpapakita ng maikling floating alert na saved na ang state
                        alertBox.classList.remove('d-none');
                        setTimeout(() => {
                            alertBox.classList.add('d-none');
                        }, 2000); // Mawawala pagkatapos ng 2 segundo
                    }
                })
                .catch(error => console.error('Error saving settings:', error));
            }

            // Mag-trigger ng auto-save kapag binago ang switch or dropdown options
            notifShow.addEventListener('change', savePreferences);
            notifSound.addEventListener('change', savePreferences);
            deleteInterval.addEventListener('change', savePreferences);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>