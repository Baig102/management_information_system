<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Account Head</th>
            <th>Main Group</th>
            <th>Sub Group 1</th>
            <th>Sub Group 2</th>
            <th>Detailed Group</th>
            <th>Sales</th>
            <th>Vendor</th>
            <th>Business Customer</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->id }}</td>
                <td>{{ $account->account_head }}</td>
                <td>{{ $account->main_group }}</td>
                <td>{{ $account->sub_group_1 }}</td>
                <td>{{ $account->sub_group_2 }}</td>
                <td>{{ $account->detailed_group }}</td>
                <td>{{ $account->sales }}</td>
                <td>{{ $account->vendor->name ?? '-' }}</td>
                <td>{{ $account->customer->name ?? '-' }}</td>
                <td>{{ $account->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>