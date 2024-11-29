@if (session('status'))
    <div class="mb-4 text-sm text-white bg-green-700 rounded-lg p-4" role="alert" id="statusMessage">
        {{ session('status') }}
    </div>
    <script>
        setTimeout(function() {
            const statusMessage = document.getElementById('statusMessage');
            if (statusMessage) {
                statusMessage.style.display = 'none';
            }
        }, 3000);
    </script>
@endif
