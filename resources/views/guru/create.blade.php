<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soal - {{$uji->nama_ujian}}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <style>
        .soal-card {
            border: 1px solid #dbdbdb;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 20px;
            background: #fafafa;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <section class="section">
        <div class="container">
            <!-- FORM INI SUDAH SESUAI DENGAN CONTROLLER def() -->
            <form action="{{ route('guru.ujian.sold', $uji->id) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  id="formSoal">
                
                @csrf
                
                <!-- Hidden fields WAJIB sesuai validasi controller -->
                <input type="hidden" name="guru_id" value="{{ $uji->guru_id }}">
                <input type="hidden" name="mapel_id" value="{{ $uji->mapel }}">

                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">
                            Tambah Soal untuk: {{ $uji->nama_ujian }}
                        </p>
                    </div>
                    
                    <div class="card-content">
                        <!-- Container untuk soal-soal -->
                        <div id="soalContainer">
                            <!-- SOAL 1 (default) -->
                            <div class="soal-card" id="soal-0">
                                <div class="field">
                                    <label class="label required">Pertanyaan</label>
                                    <div class="control">
                                        <textarea class="textarea" 
                                                  name="soal[0][soal]" 
                                                  rows="3" 
                                                  required>{{ old('soal.0.soal') }}</textarea>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label">Gambar (opsional)</label>
                                    <div class="file has-name">
                                        <label class="file-label">
                                            <input class="file-input" 
                                                   type="file" 
                                                   name="soal[0][gambar]" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                                   onchange="previewImage(this, 0)">
                                            <span class="file-cta">
                                                <span class="file-icon">
                                                    <i class="fas fa-upload"></i>
                                                </span>
                                                <span class="file-label">
                                                    Pilih gambar...
                                                </span>
                                            </span>
                                            <span class="file-name" id="file-name-0">
                                                Belum ada file
                                            </span>
                                        </label>
                                    </div>
                                    <div class="preview-image" id="preview-0">
                                        <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <div class="columns is-multiline">
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi A</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_a]" 
                                                       value="{{ old('soal.0.opsi_a') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi B</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_b]" 
                                                       value="{{ old('soal.0.opsi_b') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi C</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_c]" 
                                                       value="{{ old('soal.0.opsi_c') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="column is-half">
                                        <div class="field">
                                            <label class="label required">Opsi D</label>
                                            <div class="control">
                                                <input class="input" 
                                                       type="text" 
                                                       name="soal[0][opsi_d]" 
                                                       value="{{ old('soal.0.opsi_d') }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label class="label required">Jawaban Benar</label>
                                    <div class="control">
                                        <div class="select">
                                            <select name="soal[0][jawaban_benar]" required>
                                                <option value="">Pilih jawaban benar</option>
                                                <option value="a" {{ old('soal.0.jawaban_benar') == 'a' ? 'selected' : '' }}>A</option>
                                                <option value="b" {{ old('soal.0.jawaban_benar') == 'b' ? 'selected' : '' }}>B</option>
                                                <option value="c" {{ old('soal.0.jawaban_benar') == 'c' ? 'selected' : '' }}>C</option>
                                                <option value="d" {{ old('soal.0.jawaban_benar') == 'd' ? 'selected' : '' }}>D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Tambah Soal -->
                        <div class="field">
                            <button type="button" class="button is-info" id="tambahSoal">
                                <span class="icon">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span>Tambah Soal</span>
                            </button>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="field">
                            <button type="submit" class="button is-success is-large" id="submitBtn">
                                <span class="icon">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span>Simpan Semua Soal</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Tampilkan error jika ada -->
            @if($errors->any())
                <div class="notification is-danger mt-3">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tampilkan session message -->
            @if(session('success'))
                <div class="notification is-success mt-3">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="notification is-danger mt-3">
                    {{ session('error') }}
                </div>
            @endif
        </div>
    </section>

    <script>
        let soalCounter = 1; // Mulai dari 1 karena sudah ada soal 0

        // Fungsi tambah soal
        document.getElementById('tambahSoal').addEventListener('click', function() {
            const container = document.getElementById('soalContainer');
            const template = document.getElementById('soal-0').cloneNode(true);
            
            // Update ID dan nama field
            template.id = 'soal-' + soalCounter;
            
            // Update semua name attribute
            template.querySelectorAll('[name]').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    const newName = name.replace('[0]', '[' + soalCounter + ']');
                    el.setAttribute('name', newName);
                }
            });
            
            // Reset nilai input
            template.querySelectorAll('input[type="text"], textarea').forEach(el => {
                el.value = '';
            });
            
            template.querySelectorAll('select').forEach(el => {
                el.value = '';
            });
            
            // Reset preview
            const previewId = 'preview-' + soalCounter;
            const fileNameId = 'file-name-' + soalCounter;
            
            const previewDiv = template.querySelector('.preview-image');
            if (previewDiv) {
                previewDiv.id = previewId;
                previewDiv.style.display = 'none';
                previewDiv.querySelector('img').src = '';
            }
            
            const fileNameSpan = template.querySelector('.file-name');
            if (fileNameSpan) {
                fileNameSpan.id = fileNameId;
                fileNameSpan.textContent = 'Belum ada file';
            }
            
            // Update onchange untuk file input
            const fileInput = template.querySelector('.file-input');
            if (fileInput) {
                fileInput.setAttribute('onchange', 'previewImage(this, ' + soalCounter + ')');
            }
            
            container.appendChild(template);
            soalCounter++;
        });

        // Fungsi preview gambar
        function previewImage(input, index) {
            const preview = document.getElementById('preview-' + index);
            const fileName = document.getElementById('file-name-' + index);
            const file = input.files[0];
            
            if (file) {
                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('File harus berupa gambar (JPG, PNG, GIF)');
                    input.value = '';
                    return;
                }
                
                // Validasi ukuran (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    input.value = '';
                    return;
                }
                
                // Tampilkan preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.querySelector('img').src = e.target.result;
                    preview.style.display = 'block';
                    fileName.textContent = file.name;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                fileName.textContent = 'Belum ada file';
            }
        }

        // Debug: Cek apakah form bisa disubmit
        document.getElementById('formSoal').addEventListener('submit', function(e) {
            console.log('Form akan disubmit ke:', this.action);
            console.log('Data form:', new FormData(this));
            
            // Validasi sederhana
            const soalCards = document.querySelectorAll('.soal-card');
            if (soalCards.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 soal!');
                return false;
            }
            
            // Tampilkan loading
            document.getElementById('submitBtn').classList.add('is-loading');
            
            return true;
        });
    </script>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>