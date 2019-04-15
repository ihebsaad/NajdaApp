@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}
    <label for="emetteur">emetteur:</label>
    <input id="emetteur" type="text" class="form-control" name="emetteur"  value={{ $entree->emetteur }} />
</div>
<div class="form-group">
    <label for="sujet">sujet :</label>
    <input id="sujet" type="text" class="form-control" name="sujet"  value={{ $entree->sujet }} />
</div>
<div class="form-group">
    <label for="contenu">contenu:</label>
    <div class="form-control" style="min-height:200px">

    <?php $contenu= $entree['contenu'];echo $contenu;?>
    </div>


 </div>
<div class="form-group">
     <label for="date">date:</label>
    <?php echo  date('d/m/Y', strtotime($entree->reception)) ; ?>
</div>
@endsection