<x-layouts.app :title="__('Dashboard')">
    <div class="container mx-auto px-4 py-6" x-data="realTime()">
        <div
            class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-500 
           dark:from-indigo-700 dark:to-indigo-600 p-8 md:p-12 shadow-lg">

            <div class="flex flex-col md:flex-row items-center gap-10">
                <!-- TEXT -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl md:text-4xl font-bold mb-3">
                        Dashboard e-Kurikulum
                    </h1>

                    <p class="text-white/90 max-w-xl leading-relaxed mb-6">
                        Terwujudnya Politeknik yang Unggul, Inovatif dan Terkemuka Berbasis Teknologi Terapan pada Tahun
                        2032"


                    </p>

                    <div class="inline-flex items-center gap-2 bg-white/90 text-gray-700 
                       dark:bg-gray-900 dark:text-gray-200
                       text-sm px-4 py-2 rounded-lg shadow"
                        x-text="time">
                    </div>
                </div>

                <!-- LOGO -->
                <div class="flex-1 flex justify-center md:justify-end">
                    <img src="{{ asset('images/logo-polkam.png') }}" class="w-44 md:w-60 drop-shadow-xl"
                        alt="Logo Politeknik Kampar">
                </div>
            </div>
        </div>

        <livewire:widget.crad-statistik />
        
    </div>
    <script>
        function realTime() {
            return {
                time: '',
                timer: null,

                init() {
                    this.updateTime();

                    // Cegah double interval saat Alpine reload
                    if (this.timer) clearInterval(this.timer);

                    this.timer = setInterval(() => {
                        this.updateTime();
                    }, 1000);
                },

                updateTime() {
                    const months = [
                        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    const d = new Date();

                    const day = String(d.getDate()).padStart(2, '0');
                    const month = months[d.getMonth()];
                    const year = d.getFullYear();

                    const hh = String(d.getHours()).padStart(2, '0');
                    const mm = String(d.getMinutes()).padStart(2, '0');
                    const ss = String(d.getSeconds()).padStart(2, '0');

                    this.time = `${day} ${month} ${year}   ${hh} : ${mm} : ${ss}`;
                }
            };
        }
    </script>

</x-layouts.app>
