@extends('layouts.default')
@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label">Income Report</h3>
        </div>

    </div>

    <div class="card-body">
        <table class="table" id="myTable" width="100%">
            <thead>
            <tr>
              <th style="width: 10px">SL</th>
              <th>Feature Image</th>
              <th>Name</th>
              <th>Active Status</th>
              <th style="width: 60px">Action</th></tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
              <tr>
                <td>
                  {{ $loop->iteration }}
                </td>
                <td>
                  <img src="{{ asset('storage/'.($post->image ? $post->image : 'images/default.png')) }}" alt="avatar" height="60px" width="60px" />
                </td>
                <td>
                  {{$post->name}}
                </td>
                <td>
                  {{$post->status ? 'active' : 'Inactive'}}
                </td>
                <td>
                  <form method="post" action="{{ route('post.destroy',$post->id) }}">
                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-sm btn-clean btn-icon text-dark"><i class="la la-edit icon-lg"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-sm text-hover-danger btn-clean btn-icon"><i class="la la-trash icon-lg"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
    </div>
</div>

@endsection
