<x-layout>
<!-- The section above is the top wrapper for the layout -->
 
@include('partials._hero')
@include('partials._search')
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
@unless(count($listings) == 0)

@foreach($listings as $listing)
    <x-listing-card :listing="$listing"/>
@endforeach

@else
<p>No listings found</p>

@endunless
</div>

<!-- Showing the links to the pages after the pagination process -->
<div class="mt-6 p-4">
    {{$listings->links()}}
</div>
<!-- The endsection below is the bottom wrapper for the layout -->
</x-layout>

