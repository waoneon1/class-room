@extends('guru.layout')

@section('title', 'Dashboard Guru')

@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-500">Selamat datang,</p>
    <h1 class="text-2xl font-bold text-gray-800">{{ auth()->user()->nama_lengkap }}</h1>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">Total Materi</p>
        <p class="text-3xl font-bold text-primary">{{ $totalMateri }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">Total Tugas</p>
        <p class="text-3xl font-bold text-primary">{{ $totalTugas }}</p>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p class="text-sm text-gray-500 mb-1">Belum Dinilai</p>
        <p class="text-3xl font-bold text-yellow-500">{{ $belumDinilai }}</p>
    </div>
</div>

<div class="mt-8 bg-white rounded-2xl shadow-sm p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Statistik Nilai Siswa</h2>
            <p class="text-sm text-gray-500">Performa nilai untuk setiap tugas yang sudah dinilai.</p>
        </div>
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <select name="mata_pelajaran_id" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mapelList as $m)
                    <option value="{{ $m->id }}" {{ $mapelId == $m->id ? 'selected' : '' }}>{{ $m->nama_pelajaran }}</option>
                @endforeach
            </select>
            <select name="tugas_id" onchange="this.form.submit()" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary max-w-[200px] truncate">
                <option value="">Pilih Tugas</option>
                @foreach($tugasList as $t)
                    <option value="{{ $t->id }}" {{ $tugasId == $t->id ? 'selected' : '' }}>{{ $t->judul }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if(empty($chartLabels))
        <div class="py-12 text-center">
            <p class="text-gray-400 text-sm">Belum ada data nilai yang bisa ditampilkan untuk filter ini.</p>
        </div>
    @else
        <div class="relative h-[300px] w-full">
            <canvas id="nilaiChart"></canvas>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(!empty($chartLabels))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('nilaiChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Nilai Tugas: {{ $selectedTugasName }}',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(52, 211, 153, 0.7)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });
</script>
@endif
@endsection
