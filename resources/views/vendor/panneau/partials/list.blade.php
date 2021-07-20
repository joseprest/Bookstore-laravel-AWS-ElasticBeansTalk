<div 
    data-react="List" 
    @foreach($attributes as $key => $value)
        data-{{ $key }}="{{ $value }}" 
    @endforeach
></div>
