@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/leaflet.css') }}">
@endpush
<div class="row">
    <div class="col-sm-12">
        <x-form id="save-location-data-form">
            @method('PUT')
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-20 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    Location Details
                </h4>
                <input type="hidden" name="id" value="{{ $location->id }}">
                <div class="row p-20">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="mapId" style="height: 400px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="code"
                                    :fieldLabel="__('modules.locations.code')"
                                    fieldName="code"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('modules.locations.placeholders.code')"
                                    :fieldValue="$location->code ?? ''"></x-forms.text>
                            </div>
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="description"
                                    :fieldLabel="__('modules.locations.description')"
                                    fieldName="description"
                                    :fieldPlaceholder="__('modules.locations.placeholders.description')"
                                    :fieldValue="$location->description ?? ''"></x-forms.text>
                            </div>
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="latitude"
                                    :fieldLabel="__('modules.locations.latitude')"
                                    fieldName="latitude"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('modules.locations.placeholders.latitude')"
                                    :fieldValue="$location->latitude ?? ''"
                                ></x-forms.text>
                            </div>
                            <div class="col-md-6">
                                <x-forms.text
                                    fieldId="longitude"
                                    :fieldLabel="__('modules.locations.longitude')"
                                    fieldName="longitude"
                                    fieldRequired="true"
                                    :fieldPlaceholder="__('modules.locations.placeholders.longitude')"
                                    :fieldValue="$location->longitude ?? ''"
                                ></x-forms.text>
                            </div>
                            <div class="col-md-6">
                                <x-forms.select fieldId="client_id"
                                                fieldRequired="true"
                                                :fieldLabel="__('modules.locations.client')"
                                                fieldName="client_id">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                            <div class="col-md-6">
                                <x-forms.select fieldId="location_type_id"
                                                fieldRequired="true"
                                                :fieldLabel="__('modules.locations.type')"
                                                fieldName="location_type_id">
                                    @foreach($locationTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->code . ' - ' . $type->description }}
                                        </option>
                                    @endforeach
                                </x-forms.select>
                            </div>
                        </div>
                    </div>
                </div>
                <x-forms.actions>
                    <x-forms.button-primary
                        id="save-location-form"
                        class="mr-3"
                        icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel
                        :link="route('admin.location.locations.index')"
                        class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-forms.actions>
            </div>
        </x-form>
    </div>
</div>

<script src="{{ asset('vendor/leaflet/leaflet.js') }}"></script>
<script>
    $(document).ready(function() {
        const mapCenter = [{{ $location->latitude ?? 0 }}, {{ $location->longitude ?? 0 }}];
        const map = L.map('mapId').setView(mapCenter, 18);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'UTE Locker',
            maxZoom: 20,
        }).addTo(map);

        const marker = L.marker(mapCenter).addTo(map);
        function updateMarker(lat, lng) {
            marker
                .setLatLng([lat, lng])
                .bindPopup("Your location :  " + marker.getLatLng().toString())
                .openPopup();
            return false;
        }

        map.on('click', function(e) {
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);
            $('#latitude').val(latitude);
            $('#longitude').val(longitude);
            updateMarker(latitude, longitude);
        });


        const updateMarkerByInputs = function() {
            return updateMarker( $('#latitude').val() , $('#longitude').val());
        }
        $('#latitude').on('input', updateMarkerByInputs);
        $('#longitude').on('input', updateMarkerByInputs);
    });

    $('#save-location-form').click(function() {
        const url = "{{ route('admin.location.locations.update', $location->id) }}";
        const data = $('#save-location-data-form').serialize();

        saveLocation(data, url, '#save-location-form');
    });

    function saveLocation(data, url, buttonSelector) {
        $.easyAjax({
            url: url,
            container: '#save-location-data-form',
            type: "POST",
            disableButton: buttonSelector,
            file: true,
            data: data,
            success: function(response) {
                if (response.status === 'success') {
                    window.location.href = response.redirectUrl;
                }
            }
        })
    }
</script>
