<table id="myTable" class="table" style="width:100%">
    <thead>
        <tr>
            <th>Store Name</th>
            <th>Role</th>
            <th style="width: 60px">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($employees as $employee)
            <tr>
                <td>{{ $employee->name }}</td>
                <td>

                    @foreach ($employee->roles as $role)
                        <span
                            class="label font-weight-bold label-lg label-light-success label-inline">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td> <a class="btn btn-sm text-hover-danger btn-clean btn-icon"
                        href="{{ route('employee.remove', $employee->id) }}"><i class="la la-trash icon-lg"></i></a></td>
            </tr>
        @endforeach
    </tbody>
</table>
