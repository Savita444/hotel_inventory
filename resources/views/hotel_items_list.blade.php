<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Inventory</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        .hotel-info {
            background: #f4f4f4;
            padding: 10px;
            border-left: 5px solid #007bff;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 20px;
            color: #007bff;
        }

        ul {
            list-style-type: square;
            padding-left: 20px;
        }

        li {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <h1>Items List for Hotel: {{ $hotel->hotel_name }}</h1>

    <div class="hotel-info">
        <p><strong>Contact No:</strong> {{ $hotel->contact_no }}<strong>Email:</strong> {{ $hotel->email }}
            <strong>Address:</strong> {{ $hotel->address }}<strong>Website:</strong> <a href="{{ $hotel->website }}"
                target="_blank">{{ $hotel->website }}</a>
        </p>
    </div>

    @forelse ($items as $categoryName => $categoryItems)
        <h2>{{ $categoryName }}</h2>
        <ul>
            @foreach ($categoryItems as $item)
                {{-- <li> {{ $item->item_name }} {{ $item->name }} - {{ $item->unit_name }} ({{ $item->quantity }})</li> --}}
                <table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Price</th>


                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->unit_name }}</td>
                            <td>{{ $item->price }}</td>


                        </tr>

                    </tbody>
                </table>
            @endforeach
        </ul>
    @empty
        <p>No items found for this hotel.</p>
    @endforelse

</body>

</html>
