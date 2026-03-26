<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mall Parking Guard Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 24px;
            background: #f6f6f8;
            color: #1f2937;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            padding: 8px;
            font-size: 14px;
        }

        input,
        select,
        button {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 12px;
            padding: 8px;
            font-size: 14px;
        }

        button {
            cursor: pointer;
            background: #111827;
            color: #fff;
            border: 0;
            border-radius: 4px;
        }

        .status {
            padding: 10px;
            border-radius: 6px;
            background: #dcfce7;
            border: 1px solid #86efac;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<h1>Mall Parking Guard Dashboard</h1>
<p>Simple page for assigning and checking out parking cards.</p>

@if (session('status'))
    <div class="status">{{ session('status') }}</div>
@endif

<div class="grid">
    <div class="card">
        <h2>Current Parking Sections</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Floor</th>
                <th>Section</th>
                <th>Available</th>
                <th>Max</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($sections as $section)
                <tr>
                    <td>{{ $section->id }}</td>
                    <td>{{ $section->floor }}</td>
                    <td>{{ $section->section_name }}</td>
                    <td>{{ $section->available_slots }}</td>
                    <td>{{ $section->max_slots }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card">
        <h2>Park a Driver</h2>
        <form method="POST" action="/park">
            @csrf
            <label for="section_id">Section</label>
            <select id="section_id" name="section_id">
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">
                        Floor {{ $section->floor }} - Section {{ $section->section_name }}
                    </option>
                @endforeach
            </select>

            <label for="plate_number">Plate Number (optional)</label>
            <input id="plate_number" name="plate_number" placeholder="ABC-1234">

            <button type="submit">Issue Parking Card</button>
        </form>
    </div>

    <div class="card">
        <h2>Checkout a Driver</h2>
        <form method="POST" action="/checkout">
            @csrf
            <label for="card_id">Parking Card ID</label>
            <input id="card_id" name="card_id" placeholder="Card ID">

            <button type="submit">Checkout</button>
        </form>
    </div>

    <div class="card">
        <h2>Active Cards</h2>
        <table>
            <thead>
            <tr>
                <th>Card ID</th>
                <th>Section ID</th>
                <th>Plate</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($activeCards as $card)
                <tr>
                    <td>{{ $card->id }}</td>
                    <td>{{ $card->parking_section_id }}</td>
                    <td>{{ $card->plate_number }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No active cards</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 18px;">
    <h2>Recent Card History (Last 5)</h2>
    <table>
        <thead>
        <tr>
            <th>Card ID</th>
            <th>Section ID</th>
            <th>Active</th>
            <th>Created</th>
            <th>Checked Out</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($lastFiveCards as $card)
            <tr>
                <td>{{ $card->id }}</td>
                <td>{{ $card->parking_section_id }}</td>
                <td>{{ $card->is_active ? 'Yes' : 'No' }}</td>
                <td>{{ $card->created_at }}</td>
                <td>{{ $card->checked_out_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No records yet</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
