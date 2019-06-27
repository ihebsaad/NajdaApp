 <h2>Envoyer un SMS</h2>

    <form method="post" action="{{action('EmailController@sendsms')}}" >
       <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

        <div class="form-group">
            {{ csrf_field() }}
            <label for="description">Description:</label>
            <input id="description" type="text" class="form-control" name="description"     />
     </div>

    <div class="form-group">

        <label for="destinataire">Destinataire:</label>
        <input id="destinataire" type="number" class="form-control" name="destinataire"     />
    </div>

    <div class="form-group">
        <label for="contenu">Message:</label>
        <textarea  type="text" class="form-control" name="message"></textarea>
    </div>
        {!! NoCaptcha::renderJs() !!}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <div class="form-group">
        <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
        </div>

</form>