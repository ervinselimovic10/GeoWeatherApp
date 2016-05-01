@extends('geo.base')

@section('body')
<div class="container">
  <div class="row">

  <div class="col-md-4">
  </div>

  <div class="col-md-4">
    <h1>Vreme Slovenija</h1>
    <h5 class="lead">Sveža vremenska napoved najbližje vaši lokaciji</h5>
    <p><strong>Enostavno v dveh korakih:</strong></p>
    <p><small>1. Stisni Pridobi mojo Lokacijo</small></p>
    <p><small>2. Stisni Vremenska Napoved</small></p>
    <br/>

    @if (isset($desc))
    <a href="/" class="btn btn-default center-block">Ponovi</a>
    @else 
    <button class="btn btn-lg btn-info center-block" id="getLoc">Pridobi mojo Lokacijo</button>
    @endif

    <form action="/" method="post" role="form" class="weatherform">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="form-group">
        <input type="hidden" id="lat" name="lat" class="form-control" readonly/>
        <input type="hidden" id="lon" name="lon" class="form-control" readonly/>
      </div>
        <input type="submit" class="btn btn-lg btn-success center-block nodisplay" id="submit" value="Vremenska Napoved"/>
    </form>

    <hr/>

    @if (isset($city) && isset($weather) && isset($desc))
      <p><strong>{{ $city }}</strong></p>
      <h1>{{ $weather }}</h1>
      <div class="desc">{!! $desc !!}</div>
    @endif

    <br/>

    <div id="imgloc"></div>
    <div id="wait"></div>

    <br/>
  </div>

  <div class="col-md-4">
  </div>

</div>
</div>
@stop