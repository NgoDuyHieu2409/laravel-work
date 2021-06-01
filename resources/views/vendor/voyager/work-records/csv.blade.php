<!-- Todo: Tạm thời không dùng do lỗi font chữ -->
<table>
    <thead>
        <tr>
            <th>Work name</th>
            <th class="text-center">Date</th>
            <th class="text-center">Work name</th>
            <th class="text-center">Gender</th>
            <th class="text-center">Base wage</th>
            <th class="text-center">Transportation-fee</th>
            <th class="text-center">Total wage</th>
            <th class="text-center">Transfer request</th>
            <th class="text-center">Transfered</th>
            <th class="text-center">Commission-fee</th>
            <th class="text-center">Commission-fee-tax</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($record_by_month as $records)
        @foreach ($records as $record)
        <tr>
            <td class="text-primary"><span title="{{$record->work->title}}">{{ $record->work_title}}</span></td>
            <td>{{ $record->work_date }}</td>
            <td>{{ $record->worker->name ?? '' }}</td>
            <td class="text-center">{{ $record->gender }}</td>
            <td>{{ $record->base_wage . setting('admin.currency')}}</td>
            <td>{{ $record->transportation_fee . setting('admin.currency')}}</td>
            <td>{{ $record->total_wage . setting('admin.currency')}}</td>
            <td>{{ $record->transfer_requested_at }}</td>
            <td>{{ $record->transfered_at }}</td>
            <td>{{ $record->commission_fee . setting('admin.currency')}}</td>
            <td>{{ $record->commission_fee_tax . setting('admin.currency')}}</td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
</table>