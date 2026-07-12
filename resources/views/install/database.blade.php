@extends('layouts.install')

@section('content')
<div class="p-6 lg:p-8">
    <h2 class="font-jakarta text-xl font-bold text-gray-900 mb-1">Database Setup</h2>
    <p class="text-gray-500 text-sm mb-4">Enter your MySQL credentials below.</p>

    @if($mode === 'demo')
        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg text-sm text-orange-800">
            <strong>Demo Mode:</strong> Tables + demo data will be imported. You'll go straight to login.
        </div>
    @else
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-sm text-blue-800">
            <strong>Business Mode:</strong> Clean tables created. Next: create your admin account.
        </div>
    @endif

    <div id="errorBox" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
        <p id="errorText" class="text-sm text-red-700"></p>
    </div>

    <!-- Form (hidden during install) -->
    <div id="formSection">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                    <input type="text" id="db_host" value="127.0.0.1" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Port</label>
                    <input type="number" id="db_port" value="3306" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                <input type="text" id="db_name" value="xapiverse_db" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                <p class="mt-1 text-xs text-gray-400">Create this database in phpMyAdmin first.</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" id="db_user" value="root" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="db_password" value="" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 outline-none" placeholder="Empty if none">
                </div>
            </div>
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-500"><strong>XAMPP:</strong> Host <code>127.0.0.1</code> · Port <code>3306</code> · User <code>root</code> · Pass <em>(empty)</em></p>
            </div>
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('install.mode') }}" class="inline-flex items-center px-4 py-2 text-gray-600 text-sm font-medium hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg> Back
            </a>
            <button onclick="startInstall()" id="installBtn" class="inline-flex items-center px-6 py-2.5 bg-brand-600 text-white text-sm font-medium rounded-lg hover:bg-brand-700 transition-colors">
                {{ $mode === 'demo' ? 'Install & Finish' : 'Import & Next' }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <!-- Progress (shown during install) -->
    <div id="progressSection" class="hidden">
        <div class="text-center py-6">
            <svg class="w-10 h-10 text-brand-600 animate-spin mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p id="stepMessage" class="text-sm font-medium text-gray-800 mb-2">Starting installation...</p>
            <div class="w-full max-w-xs mx-auto h-2 bg-gray-200 rounded-full overflow-hidden">
                <div id="progressBar" class="h-full bg-brand-600 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="stepDetail" class="text-xs text-gray-500 mt-2">Step 0 of 0</p>
        </div>
    </div>
</div>

<script>
async function startInstall() {
    var host = document.getElementById('db_host').value;
    var port = document.getElementById('db_port').value;
    var name = document.getElementById('db_name').value;
    var user = document.getElementById('db_user').value;
    var pass = document.getElementById('db_password').value;

    if (!host || !port || !name || !user) {
        showError('Please fill in all required fields.');
        return;
    }

    // Hide form, show progress
    document.getElementById('formSection').style.display = 'none';
    document.getElementById('progressSection').classList.remove('hidden');
    document.getElementById('errorBox').classList.add('hidden');

    var step = 0;
    var total = 18;

    // Get base URL dynamically (works with artisan serve AND XAMPP subfolder)
    var baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';
    // Remove trailing slash
    baseUrl = baseUrl.replace(/\/$/, '');

    while (true) {
        updateProgress(step, total, 'Installing...');

        try {
            var response = await fetch(baseUrl + '/install-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    step: step,
                    db_host: host,
                    db_port: port,
                    db_name: name,
                    db_user: user,
                    db_password: pass,
                    mode: '{{ $mode }}'
                })
            });

            var text = await response.text();
            var data;
            try {
                data = JSON.parse(text);
            } catch(e) {
                showError('Server error (not JSON). Check PHP error logs. Response: ' + text.substring(0, 200));
                document.getElementById('formSection').style.display = 'block';
                document.getElementById('progressSection').classList.add('hidden');
                return;
            }

            if (!data.success) {
                showError(data.error || 'Server returned an error. Check your database credentials.');
                document.getElementById('formSection').style.display = 'block';
                document.getElementById('progressSection').classList.add('hidden');
                return;
            }

            updateProgress(step + 1, data.total || total, data.message);
            total = data.total || total;

            if (data.done) {
                document.getElementById('stepMessage').textContent = 'Installation complete!';
                document.getElementById('progressBar').style.width = '100%';
                setTimeout(function() {
                    window.location.href = baseUrl + data.redirect;
                }, 1000);
                return;
            }

            step = data.next;
        } catch (err) {
            showError('Request failed: ' + err.message + '. Make sure MySQL is running in XAMPP.');
            document.getElementById('formSection').style.display = 'block';
            document.getElementById('progressSection').classList.add('hidden');
            return;
        }
    }
}

function updateProgress(current, total, message) {
    var pct = Math.round((current / total) * 100);
    document.getElementById('progressBar').style.width = pct + '%';
    document.getElementById('stepMessage').textContent = message;
    document.getElementById('stepDetail').textContent = 'Step ' + current + ' of ' + total;
}

function showError(msg) {
    document.getElementById('errorBox').classList.remove('hidden');
    document.getElementById('errorText').textContent = msg;
}
</script>
@endsection
