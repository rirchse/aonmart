<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Ebook</title>
</head>

<body>
  <div style="padding: 40px 20px; border-radius: 10px; background: lightsteelblue; color: #222; text-align: center">

    {{-- @if ($info->type === 'order_completed')

    @endif --}}

    @if($info->message)
      <h4>{{ $info->message }}</h4>
    @endif

  </div>
</body>

</html>
