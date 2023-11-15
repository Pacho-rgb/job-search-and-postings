<!-- Wrapper component -->
<div {{$attributes->merge(['class' => 'bg-gray-50 border border-gray-200 rounded p-6'])}}>
    <!-- This is just setting the default styling to the element. This means that you can add additional styling too -->
    <!-- When applying this component outside -->
    {{$slot}}
</div>