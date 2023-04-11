@extends('layouts.default')
@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3>{{ request()->is('dashboard/store/purchase/*/edit') ? 'Edit Purchase' : 'Make New Purchase' }}</h3>
      </div>
    </div>

    @isset ($purchase)
      @livewire('dashboard.purchase', compact('purchase', 'store'))
    @else
      @livewire('dashboard.purchase', compact('store'))
    @endisset
  </div>
@endsection
