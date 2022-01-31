<tr>
<td class="header">
<a href="https://fidiasgold.com/" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{URL::asset('logo.jpg')}}" class="logo" alt="Fidias Gold">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
