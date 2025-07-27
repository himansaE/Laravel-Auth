<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-2xl font-bold mb-6">Google Dashboard</h1>
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Calendar Events</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Summary</th>
                    <th class="py-2 px-4 border-b">Start</th>
                    <th class="py-2 px-4 border-b">End</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($calendarEvents as $event)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $event->getSummary() }}</td>
                        <td class="py-2 px-4 border-b">{{ optional($event->getStart())->dateTime ?? $event->getStart()->date ?? '' }}</td>
                        <td class="py-2 px-4 border-b">{{ optional($event->getEnd())->dateTime ?? $event->getEnd()->date ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Tasks</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Title</th>
                    <th class="py-2 px-4 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $task->getTitle() }}</td>
                        <td class="py-2 px-4 border-b">{{ $task->getStatus() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        <h2 class="text-xl font-semibold mb-2">Emails</h2>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Message ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emails as $email)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $email->getId() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 