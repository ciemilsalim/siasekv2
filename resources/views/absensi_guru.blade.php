@extends('layout.app_siasek')

@section('content')
    <!-- Absensi Section-->
    <section class="bg-light py-5">
        <div class="container px-5">
            <div class="row gx-5 justify-content-center">
                <div class="col-xxl-8">
                    <div class="text-center my-5">
                        <h2 class="display-5 fw-bolder"><span class="text-gradient d-inline">Absensi Guru</span></h2>
                        <p class="lead fw-light mb-4">Silahkan Scan QRCode anda untuk melakukan absen.</p>
                        <img src="{{ asset('template/assets/qrcode.png') }}" alt="qrcode" width="100px" class="mb-3">
                        <div class="d-flex justify-content-center fs-2 gap-4">
                            <form action="/absensi_guru/store" method="post" id="form_masuk">
                                @csrf
                                <input type="text" name="nik" class="form-control no-border-input" id="nik" placeholder="Absen masuk">
                            </form>    
                        </div>
                        <div class="d-flex justify-content-center fs-2 gap-4">
                            <form action="/absensi_guru/pulang" method="post" id="form_pulang">
                                @csrf
                                <input type="text" name="nik" class="form-control no-border-input" id="nik_guru" placeholder="Absen pulang">
                            </form>    
                        </div><br><br>
                        <table class="table font-mute">
                            <tr>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                            </tr>
                            <tr>
                                <td>
                                    <p>Senin, Jumat : 07.00 AM</p>
                                    <p>Selasa, Rabu, Kamis : 07.15 AM</p>
                                </td>
                                <td>
                                    <p>Senin, Selasa, Rabu, Kamis : 15.00 PM</p>
                                    <p>Jumat : 11.00 AM</p>
                                </td>
                            </tr>
                        </table>
                       
                        <p class="text-muted">melakukan absen untuk murid klik <a href="/absensi">disini</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        
            var start = new Date();
			var end = new Date();
            var time = new Date().getTime();

            if (time > start.setHours(1, 0, 1) && time < end.setHours(10, 59, 0)){
                document.getElementById("form_masuk").style.display = "block";
                document.getElementById("form_pulang").style.display = "none";
                const scanner = document.getElementById('nik');
                scanner.focus();
                scanner.addEventListener('input', function() {
                // Dapatkan nilai dari input scanner
                const inputValue = scanner.value;

                // Set nilai ke form
                form_masuk.elements[1].value = inputValue;

                // Submit form secara otomatis
                form_masuk.submit();
                scanner.focus();
                });
            }
            
           if (time > start.setHours(11, 0, 0) && time < end.setHours(24, 0, 0)){
                document.getElementById("form_masuk").style.display = "none";
                document.getElementById("form_pulang").style.display = "block";

                const scanner = document.getElementById('nik_guru');
                scanner.focus();
                scanner.addEventListener('input', function() {
                // Dapatkan nilai dari input scanner
                const inputValue = scanner.value;

                // Set nilai ke form
                form_pulang.elements[1].value = inputValue;

                // Submit form secara otomatis
                form_pulang.submit();
                scanner.focus();
                });
            }
    </script>
@endsection