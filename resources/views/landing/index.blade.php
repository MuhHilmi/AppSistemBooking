@extends ('layouts.landing')

@section ('title', $siteSettings->site_name)
@section ('description', $siteSettings->hero_subheadline ?? 'Platform booking lapangan olahraga online.')

@section ('content')
    @include ('landing.partials.navbar')

    <div class="pt-20">
        @include ('landing.partials.hero')

        @include ('landing.partials.stats')

        @include ('landing.partials.how-it-work')

        @include ('landing.partials.features')

        @include ('landing.partials.fields')

        @include ('landing.partials.testimonials')

        @include ('landing.partials.cta')

        @include ('landing.partials.footer')
    </div>

@endsection
