@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">All Customers</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('customer.create') }}" class="btn btn-primary mx-1 font-weight-bolder">
          <span class="svg-icon svg-icon-md">
              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <rect x="0" y="0" width="24" height="24"></rect>
                  <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                  <path
                    d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                    fill="#000000" opacity="0.3"></path>
                </g>
              </svg>
            </span>
          Add Customer
        </a>
      </div>
    </div>

    <div class="card-header form-inline">
      <div class="form-row">
        <label class="form-label">Search By Customer Phone Number: </label>
        <div class="form-group">
          <input type="text" class="form-control" value="" style="margin:0 10px" onkeyup="
          var invbtn = document.getElementById('invBtn');
          if(this.value != ''){
            invbtn.setAttribute('href');
            invbtn.href = '/dashboard/customer-show/'+this.value;
          }else{
            invbtn.removeAttribute('href');
          }
          ">
          <a target="_blank" class="btn btn-primary" id="invBtn">View Invoice</a>
          </div>
      </div>
    </div>

    <div class="card-body">
      <table class="table" id="myTable">
        <thead>
        <tr>
          <th>Signup Date</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          {{-- <th>Address</th> --}}
          <th>Image</th>
          <th style="width: 125px">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
          <tr class="{{ $customer->is_banned ? 'banned' : '' }}">
            <td>
              {{ dateFormat($customer->created_at) }}
            </td>
            <td>
              {{ $customer->name }}
            </td>
            <td>
              {{ $customer->email }}
            </td>
            <td>
              {{ $customer->mobile ?? $customer->phone }}
            </td>
            {{-- <td>{{ $customer->address }}</td> --}}
            <td>
              <img src="{{ getImageUrl($customer->image) }}" alt="avatar" height="50px"/>
            </td>
            <td>
              @can('customer.manage-wallet')
                <x-dashboard.button-link url="{{ route('wallet.details', $customer->id) }}" tooltip="Wallet" is-icon="1" btn-type="clean">
                  <i class="la la-wallet icon-lg"></i>
                </x-dashboard.button-link>
              @endcan
              @can('customer.edit')
                <x-dashboard.button-link url="{{ route('customer.edit', $customer->id) }}" tooltip="Edit" is-icon="1" btn-type="clean">
                  <i class="la la-edit icon-lg"></i>
                </x-dashboard.button-link>
              @endcan
              @can('customer.edit')
                <x-dashboard.ban-user :user="$customer" class="btn btn-sm btn-icon btn-clean text-hover-primary" label="<i class='la la-user-times icon-lg'></i>"></x-dashboard.update-order-status>
              @endcan
              @can('customer.delete')
                <x-dashboard.delete-form action="{{ route('customer.destroy', $customer->id) }}">
                  <x-dashboard.button type="submit" is-icon="1" btn-type="clean" tooltip="Delete" confirmation="Are you sure you want to delete this customer?" class="text-hover-danger">
                    <i class="la la-trash icon-lg"></i>
                  </x-dashboard.button>
                </x-dashboard.delete-form>
              @endcan
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}"/>
  <style>
    #myTable tbody tr.banned td {
      background: #ffd9d9;
    }
  </style>
@endpush

@push('script')
  <script type="text/javascript" src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#myTable').DataTable({
        'order': [
          ['DSC']
        ]
      });
    })
  </script>
@endpush
