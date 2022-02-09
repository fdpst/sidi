<tr>
<td class="header">
<a href="https://sidi.fidiasgold.com/" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img width="250px;" src="{{URL::asset('logo.jpg')}}" class="logo" alt="Fidias Gold">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
