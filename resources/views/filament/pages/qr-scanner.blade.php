<x-filament::page>
    <div class="p-6 bg-transparent rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-blue-700 mb-6">Ticket QR Scanner</h1>

        <!-- Area Scanner -->
        <div class="flex justify-center items-center">
            <div id="interactive" class="viewport w-96 h-96 border border-blue-300 rounded-lg overflow-hidden shadow-md">
                <video id="preview" class="w-full h-full bg-black"></video>
            </div>
        </div>

        <!-- Feedback -->
        <div id="scan-result" class="mt-6 p-4 border border-gray-300 rounded-lg shadow text-center bg-white">
            <p id="scan-message" class="text-gray-600">Scan a barcode to see details...</p>
        </div>
    </div>

    @push('scripts')
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });

        scanner.addListener('scan', function (content) {
            let messageDiv = document.getElementById('scan-message');

            // Set pesan validasi (kuning)
            messageDiv.className = "text-gray-700 font-semibold"; // Ganti kelas
            messageDiv.innerHTML = `
                <div class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-gray-700 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Validating ticket...</span>
                </div>
            `;

            // Kirim data ke backend
            fetch("{{ route('ticket.verify') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ticket_code: content })
            })
            .then(response => response.json())
            .then(data => {
                let scanResultDiv = document.getElementById('scan-result');
                if (data.success) {
                    scanResultDiv.className = "mt-6 p-4 border border-gray-300 rounded-lg shadow text-center bg-green-100";
                    messageDiv.innerHTML = `
                        <span class="font-bold">${data.message}</span>
                        <div class="mt-4 text-left">
                            <p><strong>Ticket Code:</strong> <span class="text-blue-700">${data.ticket.code}</span></p>
                            <p><strong>Event:</strong> <span class="text-blue-700">${data.ticket.event}</span></p>
                            <p><strong>Owner:</strong> <span class="text-blue-700">${data.ticket.owner}</span></p>
                            <p><strong>Status:</strong> <span class="text-green-500">${data.ticket.status}</span></p>
                        </div>
                    `;
                } else {
                    scanResultDiv.className = "mt-6 p-4 border border-gray-300 rounded-lg shadow text-center bg-red-100";
                    messageDiv.innerHTML = `<span class="font-bold">${data.message}</span>`;
                }
            })
            .catch(err => {
                console.error(err);
                let scanResultDiv = document.getElementById('scan-result');
                scanResultDiv.className = "mt-6 p-4 border border-gray-300 rounded-lg shadow text-center bg-red-100";
                messageDiv.innerHTML = `<span class="font-bold">An error occurred while validating the ticket.</span>`;
            });
        });

        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                console.error('No cameras found.');
                let messageDiv = document.getElementById('scan-message');
                messageDiv.innerHTML = '<span class="text-red-500">No cameras found on this device.</span>';
            }
        }).catch(function (e) {
            console.error(e);
        });
    </script>
    @endpush
</x-filament::page>
