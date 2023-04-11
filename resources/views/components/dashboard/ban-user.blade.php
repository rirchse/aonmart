<!-- Button trigger modal -->
<button type="button" {{ $attributes }} data-toggle="modal" data-target="#ban-user-{{ $user->id }}" title="Ban User">{!! $label ?? __('Ban User') !!}</button>

@push('modals')
  <!-- Modal -->
  <div class="modal fade bd-example-return-modal-lg-client" id="ban-user-{{ $user->id }}" tabindex="-1" aria-labelledby="ban-user-{{ $user->id }}-Label" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ban-user-{{ $user->id }}-Label">{{ __('Ban User') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <form action="{{ route('customer.ban', $user->id) }}" method="POST" id="ban-user-status-form-{{ $user->id }}">
                @csrf
                @method('PUT')
                <h6 class="text-center"><strong>User Name :</strong> {{ $user->name }}</h6>
                @if ($user->email)
                  <h6 class="text-center"><strong>E-mail :</strong> {{ $user->email }}</h6>
                @endif
                <h6 class="text-center"><strong>Mobile :</strong> {{ $user->mobile }}</h6>
                <br>
                <div class="form-group">
                  <label for="reason-{{ $user->id }}">Ban Reason</label>
                  <input type="text" class="form-control" id="reason-{{ $user->id }}" name="reason" value="{{ $user->ban_reason }}" {{ $user->is_banned ? 'disabled' : '' }}>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          @if ($user->is_banned)
            <form action="{{ route('customer.unban', $user->id) }}" method="post">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-primary">Unban</button>
            </form>
          @else
            <button type="submit" form="ban-user-status-form-{{ $user->id }}" class="btn btn-primary">Ban</button>
          @endif
        </div>
      </div>
    </div>
  </div>
@endpush
