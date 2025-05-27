@extends('student.layouts.app')

@section('content')
    <div class="animate-fadeIn">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center text-yellow-500 mb-6">Tutorial Penggunaan Sistem</h1>

            <!-- Animated Toggle Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div onclick="showSoal(); playClick()"
                    class="cursor-pointer bg-gradient-to-br from-indigo-700 to-purple-700 p-6 rounded-xl shadow-xl transform hover:scale-105 transition duration-300 border-2 border-transparent hover:border-yellow-400 hover:shadow-yellow-300 text-center text-white hover-sfx">
                    <div class="text-5xl mb-2 animate-pulse">🧠</div>
                    <h3 class="text-xl font-bold">Tutorial Pengerjaan Soal</h3>
                    <p class="text-sm text-gray-200 mt-1">Belajar cara menjawab berbagai jenis soal</p>
                </div>

                <div onclick="showProfil(); playClick()"
                    class="cursor-pointer bg-gradient-to-br from-teal-700 to-green-700 p-6 rounded-xl shadow-xl transform hover:scale-105 transition duration-300 border-2 border-transparent hover:border-yellow-400 hover:shadow-yellow-300 text-center text-white hover-sfx">
                    <div class="text-5xl mb-2 animate-pulse">👤</div>
                    <h3 class="text-xl font-bold">Tutorial Edit Profil</h3>
                    <p class="text-sm text-gray-200 mt-1">Panduan mengubah informasi akun dengan mudah</p>
                </div>
            </div>

            <!-- Soal Tutorial Section -->
            <div id="soal-section" class="tutorial-section">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg mb-8">
                    <h2 class="text-2xl text-yellow-300 font-semibold">Pengerjaan Soal</h2>
                    <p class="text-white mt-4">Berikut adalah cara mengerjakan berbagai jenis soal dalam sistem kami:</p>
                    <h3 class="text-xl text-yellow-400 mt-4">🔁 Memulai atau Mengulang Misi</h3>
                    <p class="text-white mt-2">
                        Pada setiap challenge, Anda akan melihat tombol <strong>"🚀 START MISSION"</strong> jika Anda belum
                        menyelesaikan semua soal dengan benar.
                        Jika Anda telah menjawab semua soal dengan benar sebelumnya, tombol akan berubah menjadi <strong>"🔁
                            REVIEW"</strong>.
                        Tombol ini akan mengarahkan Anda untuk meninjau kembali jawaban-jawaban sebelumnya dan melihat mana
                        yang
                        benar atau salah.
                    </p>
                    <div class="bg-gray-900 p-4 rounded-lg mt-4 flex gap-4 flex-wrap justify-center items-center relative">
                        <button
                            class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black px-6 py-3 rounded-lg shadow hover:scale-105 transform transition font-bold">
                            🚀 START MISSION
                        </button>

                        <div class="w-[2px] h-12 bg-white/60 rotate-12"></div>

                        <button
                            class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-400 hover:scale-105 transform transition font-bold">
                            🔁 REVIEW
                        </button>
                    </div>


                    <h3 class="text-xl text-yellow-400 mt-4">A. Pilihan Ganda (Jawaban Banyak)</h3>
                    <p class="text-white mt-2">
                        Pada soal pilihan ganda dengan banyak jawaban, Anda bisa memilih lebih dari satu jawaban yang benar.
                        Pilihlah jawaban dengan cara mengklik pada kotak pilihan. Setelah memilih, klik tombol
                        <strong>Submit
                            Answer</strong> untuk mengirimkan jawaban Anda.
                    </p>
                    <div class="bg-gray-900 p-4 rounded-lg mt-4">
                        <h4 class="text-yellow-300 font-semibold">Contoh</h4>
                        <p class="text-white">Pilih jawaban yang benar untuk pertanyaan berikut:</p>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="space-y-4">
                                <button
                                    class="answer-btn-multi bg-gray-200 text-gray-800 p-3 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg w-full">
                                    Jawaban 1
                                </button>
                                <button
                                    class="answer-btn-multi bg-gray-200 text-gray-800 p-3 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg w-full">
                                    Jawaban 3
                                </button>
                            </div>
                            <div class="space-y-4">
                                <button
                                    class="answer-btn-multi bg-gray-200 text-gray-800 p-3 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg w-full">
                                    Jawaban 2
                                </button>
                                <button
                                    class="answer-btn-multi bg-gray-200 text-gray-800 p-3 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg w-full">
                                    Jawaban 4
                                </button>
                            </div>
                        </div>
                        <!-- Tombol Submit Answer -->
                        <button onclick="#"
                            class="mt-4 bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-400 transition transform hover:scale-105 w-full">
                            ✅ Submit Answer
                        </button>
                    </div>

                    <h3 class="text-xl text-yellow-400 mt-4">B. Pilihan Ganda (Jawaban Satu)</h3>
                    <p class="text-white mt-2">
                        Pada soal pilihan ganda dengan satu jawaban, hanya satu pilihan yang benar. Pilihlah jawaban yang
                        Anda
                        anggap benar untuk menjawab.
                    </p>
                    <div class="bg-gray-900 p-4 rounded-lg mt-4">
                        <h4 class="text-yellow-300 font-semibold">Contoh</h4>
                        <p class="text-white">Pilih jawaban yang benar untuk pertanyaan berikut:</p>
                        <button
                            class="answer-btn bg-gray-200 text-gray-800 p-4 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg">
                            Jawaban A
                        </button>
                        <button
                            class="answer-btn bg-gray-200 text-gray-800 p-4 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg">
                            Jawaban B
                        </button>
                        <button
                            class="answer-btn bg-gray-200 text-gray-800 p-4 rounded-lg text-lg font-semibold hover:bg-blue-500 transition transform hover:scale-105 shadow-lg">
                            Jawaban C
                        </button>
                    </div>

                    <h3 class="text-xl text-yellow-400 mt-4">C. Soal Essay</h3>
                    <p class="text-white mt-2">
                        Pada soal essay, Anda akan diminta untuk mengetikkan jawaban Anda di dalam sebuah
                        <strong>textarea</strong>.
                        Setelah menulis jawaban, klik tombol <strong>Submit Answer</strong> untuk mengirimkan jawaban Anda.
                    </p>
                    <div class="bg-gray-900 p-4 rounded-lg mt-4">
                        <h4 class="text-yellow-300 font-semibold">Contoh</h4>
                        <textarea class="w-full p-3 text-white bg-gray-800 rounded-lg" placeholder="Tulis jawaban Anda disini..."></textarea>
                        <button
                            class="bg-blue-500 text-white px-6 py-3 mt-3 rounded-lg shadow-md hover:bg-blue-400 transition transform hover:scale-105">
                            Submit Answer
                        </button>
                    </div>

                    <h3 class="text-xl text-yellow-400 mt-4">D. Soal True/False</h3>
                    <p class="text-white mt-2">
                        Pada soal True/False, Anda hanya perlu memilih apakah pernyataan tersebut benar atau salah.
                        Pilih salah satu jawaban untuk menjawab.
                    </p>
                    <div class="bg-gray-900 p-4 rounded-lg mt-4">
                        <button
                            class="answer-btn bg-green-600 text-white p-4 rounded-lg text-lg font-semibold hover:bg-green-800 transition transform hover:scale-105 shadow-lg">
                            ✅ True
                        </button>
                        <button
                            class="answer-btn bg-red-600 text-white p-4 rounded-lg text-lg font-semibold hover:bg-red-800 transition transform hover:scale-105 shadow-lg">
                            ❌ False
                        </button>
                    </div>
                    <h3 class="text-xl text-yellow-400 mt-6">📊 Halaman Ringkasan (Summary)</h3>
                    <p class="text-white mt-2">
                        Setelah kamu menyelesaikan semua soal dalam challenge, kamu akan diarahkan ke halaman
                        <strong>Challenge
                            Summary</strong>.
                        Di sana kamu bisa melihat durasi waktu pengerjaan, total skor, EXP yang didapat, jumlah jawaban
                        benar
                        dan salah, serta pesan motivasi yang menyemangati.
                    </p>
                    <p class="text-white mt-2">
                        Pada bagian bawah halaman summary, kamu akan menemukan dua tombol:
                    </p>
                    <ul class="list-disc list-inside text-white mt-2">
                        <li><strong>🏠 Missions:</strong> Untuk kembali ke daftar challenge yang tersedia.</li>
                        <li><strong>🔁 Retry / Review:</strong>
                            <ul class="list-disc list-inside ml-4">
                                <li><strong>Retry:</strong> Akan muncul jika kamu belum menjawab semua soal dengan benar,
                                    dan
                                    memungkinkanmu mengulang challenge.</li>
                                <li><strong>Review:</strong> Akan muncul jika kamu berhasil menjawab semua soal dengan
                                    benar,
                                    untuk melihat kembali jawaban kamu.</li>
                            </ul>
                        </li>
                    </ul>

                    <div class="bg-gray-900 p-4 rounded-lg mt-4 flex gap-4 flex-wrap justify-center">
                        <button
                            class="bg-yellow-400 text-black px-6 py-3 rounded-lg shadow hover:bg-yellow-300 transform hover:scale-105 transition font-bold">
                            🏠 Missions
                        </button>
                        <button
                            class="bg-blue-500 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-400 transform hover:scale-105 transition font-bold">
                            🔁 Retry / Review
                        </button>
                    </div>

                </div>
            </div>

            <!-- Profil Tutorial Section -->
            <div id="profil-section" class="tutorial-section hidden">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl text-yellow-300 font-semibold">Edit Profil Mahasiswa</h2>
                    <p class="text-white mt-4">
                        Menu <strong>Edit Profil</strong> memungkinkan kamu untuk memperbarui informasi pribadi secara
                        langsung
                        melalui sistem.
                        Berikut adalah hal-hal yang bisa kamu ubah dan cara melakukannya:
                    </p>

                    <ul class="mt-4 space-y-3 text-white text-sm">
                        <li>
                            ✅ <strong>Foto Profil:</strong> Klik tombol "Upload Foto" untuk mengganti gambar profil.
                            Disarankan
                            menggunakan foto formal dengan format JPG atau PNG.
                        </li>
                        <li>
                            🏠 <strong>Alamat Rumah:</strong> Masukkan alamat tempat tinggal terbaru agar data kamu tetap
                            valid.
                        </li>
                        <li>
                            🎂 <strong>Tanggal Lahir:</strong> Pastikan sesuai dengan data asli. Klik pada field kalender
                            untuk
                            memilih tanggal.
                        </li>
                        <li>
                            🕌 <strong>Agama:</strong> Pilih dari dropdown menu sesuai keyakinan kamu.
                        </li>
                        <li>
                            🚻 <strong>Jenis Kelamin:</strong> Pilih antara "Laki-laki" atau "Perempuan" pada menu pilihan.
                        </li>
                        <li>
                            📞 <strong>Nomor Telepon:</strong> Masukkan nomor aktif yang bisa dihubungi, format Indonesia
                            (+62)
                            atau dimulai dengan 0.
                        </li>
                        <li>
                            🎓 <strong>Program Studi:</strong> Pilih jurusan atau prodi kamu saat ini dari daftar yang
                            tersedia.
                        </li>
                        <li>
                            📚 <strong>Semester:</strong> Masukkan semester saat ini (contoh: 4 untuk semester empat).
                        </li>
                    </ul>

                    <p class="text-white mt-4">
                        Setelah selesai mengisi atau memperbarui informasi, jangan lupa klik tombol <strong>Save</strong>
                        agar
                        data kamu tersimpan dengan benar.
                        Perubahan akan langsung tercermin di halaman profil kamu.
                    </p>

                    <div class="mt-6 text-center">
                        <a href="{{ route('student.profile.edit') }}"
                            class="bg-yellow-400 text-black px-6 py-2 rounded-lg font-semibold shadow hover:bg-yellow-300 transition inline-block">
                            👤 Buka Halaman Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <audio id="hover-sound" src="{{ asset('sfx/hover.mp3') }}"></audio>
        <audio id="click-sound" src="{{ asset('sfx/click.mp3') }}"></audio>
    </div>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
    <!-- JavaScript untuk toggle -->
    <script>
        function showSoal() {
            document.getElementById("soal-section").classList.remove("hidden");
            document.getElementById("profil-section").classList.add("hidden");
        }

        function showProfil() {
            document.getElementById("profil-section").classList.remove("hidden");
            document.getElementById("soal-section").classList.add("hidden");
        }

        function playClick() {
            const audio = document.getElementById("click-sound");
            if (audio) {
                audio.currentTime = 0;
                audio.play();
            }
        }

        function playHoverSound() {
            const audio = document.getElementById("hover-sound");
            if (audio) {
                audio.currentTime = 0;
                audio.play();
            }
        }
        document.addEventListener("DOMContentLoaded", function() {
            const hoverElements = document.querySelectorAll(".hover-sfx");

            hoverElements.forEach(el => {
                el.addEventListener("mouseenter", playHoverSound);
            });
        });

        function rebindHoverSound() {
            const hoverElements = document.querySelectorAll(".hover-sfx");

            hoverElements.forEach(el => {
                el.removeEventListener("mouseenter", playHoverSound);
                el.addEventListener("mouseenter", playHoverSound);
            });
        }
    </script>
@endsection
