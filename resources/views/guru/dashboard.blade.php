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
@endsection
