@extends('platform::dashboard')

@section('title', 'Contacts')

@section('content')
    <div class="container">
        <h1>Customers</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>WhatsApp Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>
                            {{-- <a href="{{ route('wa-users.edit', $customer->id) }}" class="btn btn-sm btn-primary">Edit</a> --}}
                            <form action="{{ route('wa-user.destroy', $customer->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $customers->links() }}
    </div>
@endsection
