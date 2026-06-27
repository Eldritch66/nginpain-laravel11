@extends('layouts.app')

@section('title', 'Sewa Kosan & Kontrakan di Bogor | Nginapin')

@section('content')
<x-main-root>
    <div class="relative overflow-hidden bg-gradient-to-b from-white via-orange-50/30 to-neutral-50">
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-24 right-0 h-[520px] w-[520px] rounded-full bg-orange-100/35 blur-3xl"></div>
            <div class="absolute top-[38rem] left-0 h-[420px] w-[420px] rounded-full bg-amber-100/35 blur-3xl"></div>
        </div>

        <section class="relative">
            <div class="mx-auto max-w-7xl px-4 py-24 md:px-8 lg:px-12 lg:py-28">
                <div class="grid items-center gap-16 lg:grid-cols-2">
                    <div class="mx-auto max-w-xl text-center lg:text-left">
                        <div class="flex justify-center lg:justify-start">
                            <span class="inline-flex rounded-full border border-orange-200 bg-white/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest text-orange-600 shadow-sm backdrop-blur">
                                Kosan & Kontrakan &middot; Bogor
                            </span>
                        </div>

                        <h1 class="mt-6 text-4xl font-bold leading-tight tracking-tight text-neutral-900 md:text-5xl lg:text-6xl">
                            Temukan hunian
                            <span class="block text-orange-600">yang tepat</span>
                            di Bogor.
                        </h1>

                        <p class="mt-6 max-w-lg text-lg leading-relaxed text-neutral-600 mx-auto lg:mx-0">
                            Platform untuk mencari kosan maupun kontrakan di Bogor dengan proses yang lebih mudah, transparan, dan nyaman bagi penyewa maupun pemilik properti.
                        </p>

                        <div class="mt-8 flex flex-wrap justify-center lg:justify-start gap-4">
                            <a wire:navigate href="/properti" class="rounded-xl bg-orange-600 px-6 py-3 font-medium text-white transition hover:bg-orange-700">
                                Cari Properti
                            </a>
                            <a wire:navigate href="/login" class="rounded-xl border border-neutral-300 bg-white/80 px-6 py-3 font-medium text-neutral-700 transition hover:bg-white">
                                Saya Pemilik
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-2">
                            @foreach (['Kosan', 'Kontrakan', 'Bogor', 'Sewa Bulanan', 'Tanpa Ribet'] as $tag)
                                <span class="rounded-full border border-neutral-200 bg-white/80 px-3 py-1.5 text-sm text-neutral-600 shadow-sm backdrop-blur">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative">
                        <div class="overflow-hidden rounded-[32px] border border-neutral-200/80 bg-white shadow-xl">
                            <div class="h-[400px] md:h-[500px] bg-neutral-100 relative">
                                <img src="/hero-image.png" alt="Kosan dan kontrakan di Bogor" class="h-full w-full object-cover">
                            </div>
                            <div class="absolute bottom-6 left-6 right-6 rounded-2xl bg-white/90 p-4 shadow-lg backdrop-blur-md">
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-neutral-400">Tipe</p>
                                        <p class="mt-1 text-sm font-semibold text-neutral-900">Kosan & Kontrakan</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-neutral-400">Lokasi</p>
                                        <p class="mt-1 text-sm font-semibold text-neutral-900">Bogor, Jawa Barat</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] uppercase tracking-widest text-neutral-400">Status</p>
                                        <p class="mt-1 text-sm font-semibold text-green-600">Tersedia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative">
            <div class="mx-auto max-w-7xl px-4 py-24 md:px-8 lg:px-12 lg:py-28">
                <div class="grid items-center gap-20 lg:grid-cols-2">
                    <div class="max-w-xl">
                        <span class="text-sm font-semibold uppercase tracking-[0.2em] text-orange-600">Tentang Nginapin</span>
                        <h2 class="mt-4 text-4xl font-bold leading-tight tracking-tight text-neutral-900 md:text-5xl">Platform hunian yang memudahkan semua pihak.</h2>
                        <p class="mt-6 text-lg leading-relaxed text-neutral-600">
                            Nginapin membantu penyewa menemukan kosan maupun kontrakan di Bogor sekaligus membantu pemilik mengelola properti mereka dalam satu platform yang sederhana, transparan, dan mudah digunakan.
                        </p>
                        <div class="mt-10 grid gap-4 sm:grid-cols-2">
                            @foreach ([
                                ['num' => '01', 'title' => 'Kosan', 'desc' => 'Kamar dengan fasilitas bersama yang cocok untuk mahasiswa maupun pekerja.'],
                                ['num' => '02', 'title' => 'Kontrakan', 'desc' => 'Unit penuh untuk keluarga atau kelompok dengan privasi lebih.'],
                                ['num' => '03', 'title' => 'Booking Online', 'desc' => 'Reservasi langsung tanpa harus datang terlebih dahulu.'],
                                ['num' => '04', 'title' => 'Kelola Properti', 'desc' => 'Pemilik dapat mengelola properti melalui dashboard yang mudah digunakan.'],
                            ] as $item)
                                <div class="rounded-2xl border border-neutral-200 bg-white/90 p-5 shadow-sm transition hover:shadow-md">
                                    <p class="text-xs font-semibold text-neutral-400">{{ $item['num'] }}</p>
                                    <h3 class="mt-2 font-semibold text-neutral-900">{{ $item['title'] }}</h3>
                                    <p class="mt-2 text-sm leading-relaxed text-neutral-500">{{ $item['desc'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="rounded-[32px] border border-neutral-200 bg-white p-6 shadow-lg">
                            <div class="w-full max-w-md h-[400px] bg-neutral-100 rounded-2xl overflow-hidden">
                                <img src="/right-image.png" alt="Ilustrasi Properti" class="w-full h-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="relative">
            <div class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="absolute -bottom-32 -left-16 h-72 w-72 rounded-full bg-orange-100/25 blur-3xl"></div>
                <div class="absolute -top-16 right-1/4 h-56 w-56 rounded-full bg-amber-100/20 blur-3xl"></div>
            </div>

            <div class="relative mx-auto max-w-7xl px-4 py-24 md:px-8 lg:px-12 lg:py-28">
                <div class="mx-auto max-w-3xl">
                    <div class="text-center">
                        <span class="inline-block -rotate-1 rounded-lg border-2 border-neutral-900 bg-orange-500 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.2em] text-white shadow-[3px_3px_0px_#292524]">
                            Pusat Bantuan
                        </span>
                        <h2 class="mt-6 text-4xl font-black leading-tight tracking-tight text-neutral-900 md:text-5xl">
                            Apa Masalah Yang
                            <span class="relative inline-block">
                                <span class="relative z-10">Ingin Anda Sampaikan</span>
                                <span class="absolute bottom-0 left-0 right-0 h-3 bg-orange-300/60 -rotate-1"></span>
                            </span>
                            ?
                        </h2>
                        <p class="mt-4 text-lg font-medium text-neutral-600">Tim Nginapin siap bantu 24/7. Isi aja tiket di bawah, ya!</p>
                    </div>

                    <div class="mt-12">
                        <livewire:tiket.form-bantuan />
                    </div>

                    <div class="mt-6 flex flex-wrap items-center justify-center gap-3 text-sm font-medium text-neutral-500">
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-neutral-300 bg-white px-2.5 py-1 shadow-sm">
                            <svg class="size-3.5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Response &lt; 24 jam
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-neutral-300 bg-white px-2.5 py-1 shadow-sm">
                            <svg class="size-3.5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Data aman terenkripsi
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-md border border-neutral-300 bg-white px-2.5 py-1 shadow-sm">
                            <svg class="size-3.5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Konfirmasi via email
                        </span>
                    </div>
                </div>
            </div>
        </section>

    </div>
</x-main-root>
@endsection
