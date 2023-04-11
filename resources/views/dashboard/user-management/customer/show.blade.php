@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">Customer Details</h3>
      </div>
    </div>
    <div class="card-body">
      <h4>Customer Information:</h4>
      <table class="table">
        <tr>
          <th>Customer Name: </th>
          <td>{{$customer->name}}</td>
        </tr>
        <tr>
          <th>Email Address</th>
          <td>{{$customer->email}}</td>
        </tr>
        <tr>
          <th>Photo</th>
          <td>{{$customer->image}}</td>
        </tr>
        <tr>
          <th>Cover Image</th>
          <td>{{$customer->cover_image}}</td>
        </tr>
        <tr>
          <th>Mobile Number:</th>
          <td>{{$customer->mobile}}</td>
        </tr>
        <tr>
          <th>Phone Number:</th>
          <td>{{$customer->phone}}</td>
        </tr>
        <tr>
          <th>About The Customer:</th>
          <td>{{$customer->about}}</td>
        </tr>
        <tr>
          <th>Customer Type:</th>
          <td>{{$customer->type_id}}</td>
        </tr>
        <tr>
          <th>Status: </th>
          <td>{{$customer->status}}</td>
        </tr>
        <tr>
          <th>Store Name:</th>
          <td>{{$customer->store_id}}</td>
        </tr>
        <tr>
          <th>Customer Balance:</th>
          <td>{{$customer->balance}}</td>
        </tr>
        <tr>
          <th>Company Name: </th>
          <td>{{$customer->company_id}}</td>
        </tr>
        <tr>
          <th>Banded:</th>
          <td>{{$customer->is_banned}}</td>
        </tr>
        <tr>
          <th>Band Reason:</th>
          <td>{{$customer->ban_reason}}</td>
        </tr>
        <tr>
          <th>Customer Join Date:</th>
          <td>{{$customer->created_at->format('M d Y h:i:s A')}}</td>
        </tr>
      </table>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-lg-12 text-right">
          <a href="{{ route('customer.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </div>
  </div>
@endsection
