@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}
    <label for="ref">ref:</label>
    <input id="ref" type="text" class="form-control" name="ref"  value={{ $dossier->ref }} />
</div>
<div class="form-group">
    <label for="type">type :</label>
    <input id="type" type="text" class="form-control" name="type"  value={{ $dossier->type }} />
</div>
<div class="form-group">
    <label for="affecte">Agent :</label>
    <input id="affecte" type="text" class="form-control" name="affecte"  value={{ $dossier->affecte }} />
</div> 

@endsection