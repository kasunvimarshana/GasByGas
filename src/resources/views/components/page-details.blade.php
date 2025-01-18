@if(!empty($details))
    <div class="page-details">
        @foreach($details as $key => $properties)
            <div class="detail-item">
                @if(isset($properties['icon']))
                    <img src="{{ asset('icons/' . $properties['icon']) }}" alt="{{ $key }} icon" class="icon" />
                @endif
                <h3>{{ $properties['title'] ?? ucfirst($key) }}</h3>
                @if(isset($properties['tooltip']))
                    <p class="tooltip">{{ $properties['tooltip'] }}</p>
                @endif
                @if(isset($properties['details']))
                    <p class="details">{{ $properties['details'] }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif
