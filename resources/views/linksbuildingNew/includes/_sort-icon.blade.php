@if ($sortBy !== $field)
	<i  class="text-white fas fa-sort ml-1"></i>
@elseif ($sortDirection == 'asc')
	<i class="fas fa-sort-up text-white ml-1"></i>
@else
	<i class="fas fa-sort-down text-white ml-1"></i>
@endif