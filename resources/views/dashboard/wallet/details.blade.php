@extends('layouts.default')

@section('content')
  <div class="card card-custom">
    <div class="card-header">
      <div class="card-title">
        <h3 class="card-label">{{ __('E-Wallet') }}</h3>
      </div>
      <div class="card-toolbar">
        <a href="{{ route('customer.index') }}" class="btn btn-default btn-sm mr-2"><i class="la la-arrow-left"></i>Back</a>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-money-modal">Add Money</button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table table-borderless">
            <tr>
              <td style="width: 130px;">Customer Name</td>
              <td>: {{ $user->name }}</td>
            </tr>
            <tr>
              <td>Mobile</td>
              <td>: {{ $user->mobile }}</td>
            </tr>
            <tr>
              <th>Balance</th>
              <th>: {{ $user->balance }} TK</th>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <table class="table">
            <tr>
              <th>ID</th>
              <th>Date Time</th>
              <th>Amount (Tk.)</th>
              <th>Received By</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
            @foreach($ewallets as $ewallet)
            <tr>
              <td>{{$ewallet->id}}</td>
              <td>{{datetimeFormat($ewallet->created_at)}}</td>
              <td>{{$ewallet->amount}}</td>
              <td>{{\App\Models\User::find($ewallet->created_by)->name}}</td>
              <td>
                @if($ewallet->status == 0)
                <label for=""><i class="fa fa-clock"></i></label>
                @elseif($ewallet->status == 1)
                <label for=""><i class="fa fa-check"></i></label>
                @endif
              </td>
              <td>
                @if($ewallet->status == 0)
                <a href="{{route('wallet.approve', $ewallet->id)}}" style="float:left;width:15px;margin-right:10px"><i class="fa fa-check"></i></a>
                <form action="{{route('wallet.destroy', $ewallet->id)}}" method="POST">
                  @csrf
                  @method('delete')
                  <button onclick="return confirm('Are you sure, you want to delete the item?')" style="background:none;border:none" type="submit" title="Delete"><i class="fa fa-trash"></i></button>
                </form>
                @endif
              </td>
            </tr>
            @endforeach
            <tr>
              <th></th>
              <th>Total =</th>
              <th>{{$ewallets->sum('amount')}} Tk.</th>
              <th></th>
              <th></th>
              <th></th>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('modals')
  <!-- Modal -->
  <div class="modal fade" id="add-money-modal" tabindex="-1" role="dialog" aria-labelledby="add-money-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="add-money-modalLabel">Add Money</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('wallet.add-money', $user->id) }}" method="POST" id="add-money-form">
            @csrf
            @method('PUT')

            <div class="form-group">
              <label for="amount"><b>{{ __('Amount') }}&nbsp;<span class="text-danger">*</span></b></label>
              <input name="amount" id="amount" value="{{ old('amount') }}" placeholder="Amount" type="number" class="form-control" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="add-money-form">Add</button>
        </div>
      </div>
    </div>
  </div>
@endpush
