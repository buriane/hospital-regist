@if(isset($getState()['kodeBooking']))
    <div>
        <label class="text-sm font-medium text-gray-700">QR Code</label>
        <div class="mt-1">
            {!! DNS2D::getBarcodeHTML($getState()['kodeBooking'], 'QRCODE', 4, 4) !!}
        </div>
    </div>
@endif