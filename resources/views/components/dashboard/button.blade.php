<button
  type="{{ $type ?? 'submit' }}"
  {{ $attributes->merge(['class' => 'btn btn-sm ' . 'btn-' . ($btnType ?? 'primary') . (isset($isIcon) && $isIcon == true ? ' btn-icon' : '')]) }}
  @if(isset($tooltip)) data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $tooltip }}" @endif
  @if(isset($confirmation)) onclick="return confirm('{{ $confirmation }}');" @endif
>
  {{ $slot }}
</button>
