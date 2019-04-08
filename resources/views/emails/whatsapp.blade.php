@extends('layouts.mainlayout')

@section('content')
    <h2>Envoyer un SMS Whatsapp</h2>
    <form method="post" action="{{action('EmailController@sendwhatsapp')}}" >
    <div class="form-group">
        {{ csrf_field() }}
        <label for="destinataire">Destinataire:</label>
        <input id="destinataire" type="text" class="form-control" name="destinataire"    />
    </div>

    <div class="form-group">
        <label for="contenu">Message:</label>
        <textarea  type="text" class="form-control" name="message"></textarea>
        {!! NoCaptcha::renderJs() !!}

    </div>
        <div class="form-group">
        <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
        </div>

    </form>
@endsection