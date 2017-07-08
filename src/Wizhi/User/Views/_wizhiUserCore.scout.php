<!-- Default Wizhi User main view -->
@if($__factory->hasSections())
    @foreach($__sections as $section)
        <?php
            $data = $section->getData();
        ?>
        <h3>{{ $data['name'] }}</h3>
        @if(isset($__fields[$data['slug']]))
            @include('_wizhiUserTable', ['__fields' => $__fields[$data['slug']]])
        @endif
    @endforeach
@else
    @include('_wizhiUserTable', ['__fields' => $__fields])
@endif