<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img style="width:200px;height:50px" src="https://site1.p2p.com.bd/images/p2p-logo-black.png" class="logo" alt="p2p Logo">
@else
    <img style="width:200px;height:50px" src="https://site1.p2p.com.bd/images/p2p-logo-black.png" class="logo" alt="p2p Logo">
{{-- {{ $slot }} --}}
@endif
</a>
</td>
</tr>
