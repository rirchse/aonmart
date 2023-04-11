<!-- Button trigger modal -->
<button type="button" {{ $attributes }} data-toggle="modal" data-target="#updateOrderStatus-{{ $order->id }}" title="Update Order Status">{!! $label ?? __("Update Status") !!}</button>

@push('modals')
  <!-- Modal -->
  <div class="modal fade bd-example-return-modal-lg-client" id="updateOrderStatus-{{ $order->id }}" tabindex="-1" aria-labelledby="updateOrderStatus-{{ $order->id }}-Label" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateOrderStatus-{{ $order->id }}-Label">{{ __('Update Order Status') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <form action="{{ route('order.update-status', $order->id) }}" method="POST" id="update-order-status-form-{{ $order->id }}">
                @csrf
                @method('PUT')
                <h5 class="text-center"><strong>Order No :</strong> #{{ $order->order_no }}</h5>
                <br>
                <div class="form-group">
                  <label for="order-status-{{ $order->id }}">Choose Order Status</label>
                  <select class="custom-select" id="order-status{{ $order->id }}" name="order_status"
                    onChange="document.getElementById('reason-block-{{ $order->id }}').style.display = (this.value == '{{ \App\Models\Order::CANCELLED }}' ? 'block' : 'none')"
                  >
                    @foreach(\App\Models\Order::AVAILABLE_STATUSES as $statusKey => $status)
                      <option value="{{ $statusKey }}" {{ $order->order_status == $statusKey ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group" id="reason-block-{{ $order->id }}" style="display: {{ $order->order_status == \App\Models\Order::CANCELLED ? 'block' : 'none' }}">
                  <label for="cancel-reason-{{ $order->id }}">Cancel Reason</label>
                  <input type="text" class="form-control" name="cancel_reason" id="cancel-reason-{{ $order->id }}" value="{{ $order->cancel_reason }}">
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" form="update-order-status-form-{{ $order->id }}" class="btn btn-primary">Update</button>
        </div>
      </div>
    </div>
  </div>
@endpush
