<?php

use App\Models\Prize;

$current_probability = floatval(Prize::sum('probability'));
?>
{{-- TODO: add Message logic here --}}

@if($current_probability !== 100.0)
    <div class="alert alert-danger">
        {{__('Sum of all the prizes probability must be 100%.')}}
        {{__('Currently its')}} {{ $current_probability }}%
        {{__('You have yet to add')}} {{ 100.0 - $current_probability }}% {{__('to the prize.')}}
    </div>
@endif
