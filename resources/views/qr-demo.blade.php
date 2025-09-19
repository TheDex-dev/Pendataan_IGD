@extends('layout.app')

@section('title', 'QR Code Demo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">QR Code untuk Form Submission</h4>
                </div>
                <div class="card-body text-center">
                    <p class="mb-4">QR Code ini akan mengarahkan pengguna ke halaman form submission:</p>
                    
                    <div class="mb-4">
                        <img src="{{ $qrUrl }}" alt="QR Code for Form Submission" class="img-fluid" style="max-width: 400px;">
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>URL yang dikodekan:</strong><br>
                        <a href="{{ $formUrl }}" target="_blank">{{ $formUrl }}</a>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ $qrUrl }}" class="btn btn-primary" download="form-qrcode.svg">
                                <i class="fas fa-download"></i> Download QR Code
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ $formUrl }}" class="btn btn-success">
                                <i class="fas fa-external-link-alt"></i> Buka Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Penggunaan QR Code</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Public URL:</strong> <code>{{ $qrUrl }}</code>
                        </li>
                        <li class="list-group-item">
                            <strong>API Endpoint:</strong> <code>{{ url('/api/qr-code/form') }}</code>
                        </li>
                        <li class="list-group-item">
                            <strong>Target Form:</strong> <code>{{ $formUrl }}</code>
                        </li>
                    </ul>
                    
                    <div class="mt-3">
                        <h6>Cara Penggunaan:</h6>
                        <ol>
                            <li>Akses URL QR code untuk mendapatkan gambar QR</li>
                            <li>Print atau tampilkan QR code di lokasi yang mudah diakses</li>
                            <li>Pengguna dapat scan QR code dengan smartphone untuk langsung mengakses form</li>
                            <li>Form submission akan diterima dan diproses secara normal</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endpush