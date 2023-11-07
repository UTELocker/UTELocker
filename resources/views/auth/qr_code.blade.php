<x-auth>
    <div class="card">
        <div class="card-header">
            <h2>{{ $title ?? 'Qr code' }}</h2>
        </div>
        <div class="card-body">
            {!! QrCode::size(300)->generate($qrCode) !!}
        </div>
    </div>
    <x-slot name="scripts">
        <script>
        </script>
    </x-slot>
</x-auth>
