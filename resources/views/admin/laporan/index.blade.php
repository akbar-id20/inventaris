@extends('layouts.master')

@section('content')
    ini adalah halaman laporan<br/>

    <a href="/admin/laporan/barang" class="btn btn-primary" target="_blank">CETAK LAPORAN BARANG PDF</a>
    <a href="/admin/laporan/excel" class="btn btn-success">CETAK EXCEL</a>
@endsection