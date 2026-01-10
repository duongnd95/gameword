<div class="about">
    <h2 class="about-title text-uppercase text-center">{{ $intro->name }}</h2>
    <div class="about-row about-description bg-grey-medium">
        {!! $intro->content !!}
    </div>
</div>
<div class="about">
    <h2 class="about-title text-uppercase text-center">{{ $introValue->name }}</h2>
    <div class="row">
        @foreach($introValues as $item)
            <div class="col-sm-6 col-md-3 text-center about-value">
                <img src="{{ asset($item->avatar) }}" alt="{{ $item->name }}">
                <p class="about-value--title">{{ $item->name }}</p>
                <div class="about-value--description">
                    {!! $item->content !!}
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="about">
    <h2 class="about-title text-uppercase text-center">{{ $organizational->name }}</h2>
    <div class="about-row about-description bg-white">
        {!! $organizational->content !!}
    </div>
</div>
<div class="about">
    <h2 class="about-title text-uppercase text-center">{{ $leader->name }}</h2>
    <div class="row justify-content-center">
        @foreach($leadership as $item)
            <div class="col-sm-6 col-md-4 col-lg-3 text-center about-value">
                <div class="leader-item bg-white">
                    <div class="image-responsive">
                        <img class="image-responsive--lg" src="{{ asset($item->avatar) }}" alt="{{ $item->name }}">
                    </div>
                    <p class="about-value--title">{{ $item->name }}</p>
                    <div class="about-value--description">
                        {!! $item->content !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>