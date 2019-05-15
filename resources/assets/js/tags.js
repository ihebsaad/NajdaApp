var idTagger = function(selector) {
  'use strict';
  var $idTagger = { obj: $(selector) };
  if ($idTagger.obj.length < 1) { return; }
  $idTagger.names = $('ul', $idTagger.obj);
  $idTagger.field = $('input[data-allowed-tags]', $idTagger.obj).eq(0);
  $idTagger.entry = $('[data-id="entry"]', $idTagger.obj).hide();
  // convert source to UI values, uses separate 'label' in drop list and 'value' in form field if both are provided
  $idTagger.list = $.map($idTagger.field.data('allowed-tags'), function(el) { return {label: el.name, id: el.id}; });

  // Shipping Country Blacklist, restricted entries
  if ($idTagger.names.length > 0 && $idTagger.entry.length < 1) { // insert entry block if missing
    $idTagger.names.append('<li data-id="entry"><div class="tag-editor-tag active">' +
                           '<input type="text" maxlength="50" value="" size="1"></div></li>');
    $idTagger.entry = $('[data-id="entry"]', $idTagger.obj).hide();
  }

  $idTagger.names.on('click', '.tag-editor-delete', function() { // delete entry
    var urldeltag = $('input[name="urldeletetag"]').val();
    var entree = $('input[name="entree"]').val();
    var _token = $('input[name="_token"]').val();
    var titre = "tag de test";
    //alert($(this).parent().data('id'));
    if (window.confirm("Êtes-vous sûr de vouloir supprimer cet tag ?")) { 
    switch($(this).parent().data('id')) {
      case "GOP":
        titre = "Garantie de paiement";
        break;
      case "FR":
        titre = "Franchise";
        break;
      case "TT":
        titre = "tag de test";
        break;
      default:
        titre = "tag de test";
    }
    if (entree != '')
            {

                $.ajax({
                    url:urldeltag,
                    method:"POST",
                    data:{entree:entree,titre:titre, _token:_token},
                    success:function(data){
                        //alert('Added successfully');
                    }
                    ,
                    fail: function(xhr, textStatus, errorThrown){
                       alert('Erreur lors de suppression de tag');
                    }
                });
            }
    else{
        alert('ERROR url tag delete');
    }
    var $this = $(this).parent(),
        ids = $idTagger.field.val().split(','),
        idx = ids.indexOf($this.data('id'));
    $this.remove(); // remove name
    if (idx > -1) { // remove code
      ids.splice(idx, 1);
      $idTagger.field.val(ids.join(','));
    }
  }
  });

  $idTagger.obj.on('click', function() { // show and focus on field
    $idTagger.entry.show().find('input').eq(0).focus();
  }).find('[data-id="entry"] input').on('blur keyup', function(event) {
    var $this = $(this);
    if (event.type === 'blur') {
      $idTagger.entry.hide();
      $this.val('');
    } else {
      $this.prop({size: 1 + $this.val().length});
    }
  }).autocomplete({
    minLength: 2,
    source: function(request, response) {
      response($.ui.autocomplete.filter($idTagger.list, request.term));
    },
    select: function(event, ui) { // add entry
      var entree = $('input[name="entree"]').val();
      var _token = $('input[name="_token"]').val();
      var urladdtag = $('input[name="urladdtag"]').val();
      var $this = $(this).val(''), // clear entry field on selection
          ids = $idTagger.field.val().split(',');
      if (ids.indexOf(ui.item.id) < 0) { // prevent duplicates
        ids.push(ui.item.id);
        $idTagger.field.val(ids.join(',').replace(/^,+|,+$/g, '')); // trim null entries
        
        if (ui.item.label === 'Garantie de paiement')
        {
          //$('#addgop').modal('show');
          var montant = prompt("Veuillez entrer le montant:", "");
        }
            if (entree != '')
            {
            var titre = ui.item.label;

                $.ajax({
                    url:urladdtag,
                    method:"POST",
                    data:{entree:entree,titre:titre, _token:_token},
                    success:function(data){
                        
                    $idTagger.entry.before('<li data-id="' + ui.item.id + '"><div class="tag-editor-spacer">&nbsp;</div>' +
                                           '<div class="tag-editor-tag">' + ui.item.label + '</div><div class="tag-editor-delete"><i></i></div></li>');

                    }
                    ,
                    fail: function(xhr, textStatus, errorThrown){
                       alert('Erreur lors dajout de tag');
                    }
                });
            }
            else{
            alert('ERROR url tag');
            }
           
      } else { // duplicate entry warning
        $this.val('tag existe deja').prop({ size: 1 + $this.val().length })
          .effect('highlight', {}, 1000, function() { $this.val('').prop({ size: 1 + $this.val().length }); });
      }
      return false;
    }
  });
};
idTagger('#taglist');
