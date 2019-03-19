@extends('layouts.mainlayout')

@section('content')
    <div class="form-group">
        {{ csrf_field() }}
        <label for="emetteur">emetteur:</label>
        {{ $entree->emetteur }}
    </div>
    <div class="form-group">
        <label for="sujet">sujet :</label>
        {{ $entree->sujet }}
    </div>
    <div class="form-group">
        <label for="contenu">contenu:</label>
        <?php  $content= $entree->contenu; ?>
        <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
        <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

        <?php  $cont=  str_replace($search,$replace, $content); ?>
        <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
        <?php  echo $cont; ?>


<style>
    .invoice{background-color: khaki;padding:5px 5px 5px 5px;}
    label{font-weight:bold;}
</style>

    </div>
    <div class="form-group">
        <label for="date">date:</label>
        <?php echo  date('d/m/Y', strtotime($entree->reception)) ; ?>

    </div>
@endsection