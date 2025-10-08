@extends(!auth()->user() ? 'layouts.app' : 'layouts.front')
@section('title',!auth()->user()? 'Gestion des events':'Events')

@section('content')
@if(!auth()->user())
    <h1>Events</h1>
    <a href="{{ route('events.create') }}" class="btn btn-primary">Create New Event</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Tags</th>
                <th>Start Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->eventType->name ?? 'N/A' }}</td>
                    <td>{{ optional($event->tags)->count() ? $event->tags->pluck('name')->implode(', ') : 'No tags' }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $events->links() }}
@else
<style>
body{background: linear-gradient(135deg,  rgba(226,226,226,1) 0%,rgba(219,219,219,1) 50%,rgba(209,209,209,1) 50%,rgba(209,209,209,1) 50%,rgba(254,254,254,1) 100%) center/cover no-repeat;min-height:100vh}
.card{width:360px;background:#fff;border-radius:15px;box-shadow:0 5px 20px rgba(0,0,0,.1);transition:.3s;font-family:'Segoe UI',sans-serif;margin:20px auto;overflow:hidden;position:relative;cursor:pointer}
.card:hover{transform:translateY(-5px);box-shadow:0 10px 25px rgba(0,0,0,.15)}
.badge{position:absolute;top:10px;right:10px;background: linear-gradient(to right,  rgba(169,3,41,1) 0%,rgba(196,72,72,1) 44%,rgba(170,34,56,1) 100%);color:#fff;padding:5px 10px;font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,.2);z-index:10}
.tilt{overflow:hidden}
.img{height:200px;overflow:hidden}
.img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.card:hover .img img{transform:scale(1.05)}
.info{padding:20px}
.cat{font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:#71717A;margin-bottom:5px}
.title{font-size:18px;font-weight:700;color:#18181B;margin:0 0 10px;letter-spacing:-.5px}
.desc{font-size:13px;color:#52525B;line-height:1.4;margin-bottom:12px}
.feats{display:flex;gap:6px;margin-bottom:15px}
.feat{font-size:10px;background:#F4F4F5;color:#71717A;padding:3px 8px;border-radius:10px;font-weight:500}
.bottom{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.price{display:flex;flex-direction:column}
.old{font-size:13px;text-decoration:line-through;color:#A1A1AA;margin-bottom:2px}
.new{font-size:20px;font-weight:700;color:#18181B}
.btn{background:linear-gradient(45deg,#18181B,#27272A);color:#fff;border:none;border-radius:10px;padding:8px 15px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;transition:.3s;box-shadow:0 3px 10px rgba(0,0,0,.1)}
.btn:hover{background:linear-gradient(45deg,#27272A,#3F3F46);transform:translateY(-2px);box-shadow:0 5px 15px rgba(0,0,0,.15)}
.btn:before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.1),transparent);transition:.5s}
.btn:hover:before{left:100%}
.icon{transition:transform .3s}
.btn:hover .icon{transform:rotate(-10deg) scale(1.1)}
.meta{display:flex;justify-content:space-between;align-items:center;border-top:1px solid #F4F4F5;padding-top:12px}
.rating{display:flex;align-items:center;gap:2px}
.rcount{margin-left:6px;font-size:11px;color:#71717A}
.stock{font-size:11px;font-weight:600;color:#22C55E}
.events-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}
@media (max-width:400px){.card{width:90%}.title{font-size:16px}.img{height:180px}.bottom{flex-direction:column;align-items:flex-start;gap:10px}.price{margin-bottom:5px}.btn{width:100%;justify-content:center}}
</style>


    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('events.index') }}" class="mb-4">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search events..." class="p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="event_type_id" class="p-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Event Types</option>
                @foreach($eventTypes as $type)
                    <option value="{{ $type->id }}" {{ request('event_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">Filter</button>
        </div>
    </form>
    <!-- Events List -->
    <div class="events-container">
        @forelse ($events as $event)
            <div class="card">
                <div class="img">
                    @if ($event->banner_url)
                        <img src="{{ asset('storage/' . $event->banner_url) }}" alt="{{ $event->title }}" class="w-full h-full object-cover rounded">
                    @else
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500 text-sm">No Image</span>
                        </div>
                    @endif
                </div>
                <div class="info">
                    <div class="cat">{{ $event->eventType->name ?? 'N/A' }}</div>
                    <h2 class="title">{{ $event->title }}</h2>
                    <div class="cat">{{ $event->address }}, {{ $event->city }}, {{ $event->country }}</div>
                    <p class="desc">{{ Str::limit($event->description, 100) }}</p>
                    <div class="feats">
                     <span class="feat">{{ $event->tags->pluck('name')->implode(', ') }}</span>
                    </div>
                    <p class="stock">
                        {{ \Carbon\Carbon::parse($event->start_date)->format('d/M') }} {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }} -
                        @if ($event->end_date)
                            {{ \Carbon\Carbon::parse($event->end_date)->format('d/M H:i') }}
                        @else
                            {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
                        @endif
                    </p>
                </div>
                <!-- 
                <div class="btn">
                    <a href="{{ route('events.show', $event) }}" class="text-blue-500 hover:underline block">+ Add to calendar</a>
                </div>
                -->
                <button class="btn" href="{{ route('events.show', $event) }}">
                    <span>Add to calendar</span>
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </button>
                
            </div>
        @empty
            <div class="text-center text-gray-500">
                No events found.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $events->links() }}
    </div>
@endif
@endsection