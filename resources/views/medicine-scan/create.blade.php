<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-semibold text-gray-800">Scan Obat</h1>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('medicine-scan.store') }}" enctype="multipart/form-data" id="scanForm">
                    @csrf

                    <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 text-center" id="dropZone">

                        {{-- Video preview untuk webcam --}}
                        <video id="webcamVideo" class="hidden w-full rounded-xl mb-3" autoplay playsinline></video>
                        <canvas id="webcamCanvas" class="hidden"></canvas>

                        {{-- Preview hasil foto --}}
                        <div id="previewWrapper" class="hidden mb-4">
                            <img id="previewImage" class="max-h-64 mx-auto rounded-xl" />
                        </div>

                        <div id="uploadPrompt">
                            <div class="w-14 h-14 bg-cream-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-forest-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                            <p class="text-sm text-gray-600 mb-1">Arahkan kamera ke kemasan obat</p>
                            <p class="text-xs text-gray-400">Pastikan tulisan terlihat jelas</p>
                        </div>

                        <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden" required>

                        <div id="actionButtons" class="mt-4 flex gap-2 justify-center flex-wrap">
                            <button type="button" id="openCameraBtn" class="bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                                📷 Buka Kamera
                            </button>
                            <button type="button" id="chooseFileBtn" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition font-medium">
                                Pilih dari File
                            </button>
                        </div>

                        <div id="cameraActions" class="hidden mt-4 flex gap-2 justify-center">
                            <button type="button" id="captureBtn" class="bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                                📸 Ambil Foto
                            </button>
                            <button type="button" id="cancelCameraBtn" class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded-xl text-sm hover:bg-gray-50 transition font-medium">
                                Batal
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="submitBtn" class="hidden w-full mt-4 bg-forest-700 text-white px-6 py-3 rounded-xl hover:bg-forest-800 transition font-medium">
                        Analisa Sekarang
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        const photoInput = document.getElementById('photoInput');
        const previewWrapper = document.getElementById('previewWrapper');
        const previewImage = document.getElementById('previewImage');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const submitBtn = document.getElementById('submitBtn');
        const scanForm = document.getElementById('scanForm');
        const openCameraBtn = document.getElementById('openCameraBtn');
        const chooseFileBtn = document.getElementById('chooseFileBtn');
        const actionButtons = document.getElementById('actionButtons');
        const cameraActions = document.getElementById('cameraActions');
        const captureBtn = document.getElementById('captureBtn');
        const cancelCameraBtn = document.getElementById('cancelCameraBtn');
        const webcamVideo = document.getElementById('webcamVideo');
        const webcamCanvas = document.getElementById('webcamCanvas');

        let stream = null;

        function showPreview(dataUrl) {
            previewImage.src = dataUrl;
            previewWrapper.classList.remove('hidden');
            uploadPrompt.classList.add('hidden');
            actionButtons.classList.add('hidden');
            submitBtn.classList.remove('hidden');
        }

        // Pilih dari file
        chooseFileBtn.addEventListener('click', function () {
            photoInput.click();
        });

        photoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => showPreview(e.target.result);
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Buka webcam
        openCameraBtn.addEventListener('click', async function () {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                webcamVideo.srcObject = stream;
                webcamVideo.classList.remove('hidden');
                uploadPrompt.classList.add('hidden');
                actionButtons.classList.add('hidden');
                cameraActions.classList.remove('hidden');
            } catch (err) {
                alert('Tidak bisa mengakses kamera: ' + err.message + '\n\nPastikan browser diizinkan mengakses kamera, atau gunakan tombol "Pilih dari File" sebagai gantinya.');
            }
        });

        // Ambil foto dari webcam
        captureBtn.addEventListener('click', function () {
            webcamCanvas.width = webcamVideo.videoWidth;
            webcamCanvas.height = webcamVideo.videoHeight;
            webcamCanvas.getContext('2d').drawImage(webcamVideo, 0, 0);

            const dataUrl = webcamCanvas.toDataURL('image/jpeg', 0.9);
            showPreview(dataUrl);

            // Convert dataUrl jadi File, masukkan ke input file
            fetch(dataUrl)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], 'webcam-capture.jpg', { type: 'image/jpeg' });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    photoInput.files = dataTransfer.files;
                });

            stopCamera();
        });

        cancelCameraBtn.addEventListener('click', function () {
            stopCamera();
            webcamVideo.classList.add('hidden');
            actionButtons.classList.remove('hidden');
            cameraActions.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
        });

        function stopCamera() {
            webcamVideo.classList.add('hidden');
            cameraActions.classList.add('hidden');
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        scanForm.addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Menganalisa...';
        });
    </script>
</x-app-layout>