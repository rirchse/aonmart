<form method="post" action="{{ $action }}" style="display: inline-block">
  @csrf
  @method('DELETE')
  {{ $slot }}
</form>
