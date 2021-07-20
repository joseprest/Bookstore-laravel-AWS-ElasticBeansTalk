<div 
    data-react="Form" 
    @foreach($attributes as $key => $value)
        data-{{ $key }}="{{ $value }}" 
    @endforeach
></div>
