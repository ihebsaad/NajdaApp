@isset ($dossiers)
<div class="row folders" style="margin-top:20px;">
        <div class="carousel-wrap">
          <div class="owl-carousel">
            @foreach ($dossiers as $i) 
            <div class="item">
                <a class="dossieritem" href="#" id="{{ $i->id }}" >
                    <div class="dossiercr well well-gc well-sm" >
                        <h3 class="cutlongtext">{{ $i->reference_medic }}</h3>
                        <p class="cutlongtext">{!!$i->subscriber_name!!}</p>
                    </div>
                </a>
            </div>
            @endforeach
          </div>
        </div>
    </div>
@endisset