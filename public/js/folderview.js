

 $(function () {


     $('#add2').click(function(){
         var prestataire = $('#selectedprest').val();
         var dossier_id = $('#iddossupdate').val();
         var typeprest = $('#typeprest').val();
         alert(prestataire);
         alert(dossier_id);
         alert(typeprest);

         //   gouvcouv
         ///if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
         ///   {
         var _token = $('input[name="_token"]').val();
         $.ajax({
             url:"{{ route('prestations.saving') }}",
             method:"POST",
             data:{prestataire:prestataire,dossier_id:dossier_id,typeprest:typeprest, _token:_token},
             success:function(data){
                 console.log(data);
                 alert('data : '+data);
                 //    window.location =data;

             },
             error: function(jqXHR, textStatus, errorThrown) {
                 alert('msg : '.jqXHR.status);
                 alert('msg 2 : '.errorThrown);
             }

         });
         ///  }else{
         // alert('ERROR');
         /// }
     });

     $('.radio1').click(function() {

         var   div=document.getElementById('montantfr');
         if(div.style.display==='none')
         {div.style.display='block';	 }
         else
         {div.style.display='none';     }

         var   div2=document.getElementById('plafondfr');
         if(div2.style.display==='none')
         {div2.style.display='block';	 }
         else
         {div2.style.display='none';     }
     });

     $('#btn01').click(function() {

      var   div=document.getElementById('ben2');
         if(div.style.display==='none')
         {div.style.display='block';	 }
         else
         {div.style.display='none';     }


     });

     $('#btn02').click(function() {

         var   div=document.getElementById('ben3');
         if(div.style.display==='none')
         {div.style.display='block';	 }
         else
         {div.style.display='none';     }


     });


     $('#btn03').click(function() {

         var   div=document.getElementById('adresse2');
         if(div.style.display==='none')
         {div.style.display='block';	 }
         else
         {div.style.display='none';     }


     });


     $('#btn04').click(function() {

         var   div=document.getElementById('adresse3');
         if(div.style.display==='none')
         {div.style.display='block';	 }
         else
         {div.style.display='none';     }


     });

         $("#typeprest").change(function() {

         document.getElementById('termine').style.display = 'none';
         document.getElementById('showNext').style.display='none';
         document.getElementById('choisir').style.display='none';
         document.getElementById('selectedprest').value=0;

     });

         $("#gouvcouv").change(function(){
     //  prest = $(this).val();
             document.getElementById('selectedprest').value=0;

             var  type =document.getElementById('typeprest').value;
             var  gouv =document.getElementById('gouvcouv').value;
             if((type !="")&&(gouv !=""))
             {
     var _token = $('input[name="_token"]').val();

     document.getElementById('termine').style.display = 'none';

     $.ajax({
         url:"{{ route('dossiers.listepres') }}",
         method:"post",

         data:{gouv:gouv,type:type, _token:_token},
         success:function(data){

        //     alert('1'+data);
             //   alert('Added successfully');
         // alert('2'+JSON.parse((data)));
              $('#data').html(data);
         //window.location =data;
            console.log(data);
      ////       data.map((item, i) => console.log('Index:', i, 'Id:', item.id));
             var  total =document.getElementById('total').value;

            if(parseInt(total)>0)
            {
                document.getElementById('showNext').style.display='block';
             }

         }
     }); // ajax

             }else{
                 alert('SVP, Sélectionner le gouvernorat et la spécialité');
             }
 }); // change

     $("#choisir").click(function() {
         //selected= document.getElementById('selected').value;
         selected=    $("#selected").val();
         document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;


     });


     $("#essai2").click(function() {
         document.getElementById('termine').style.display = 'none';
         document.getElementById('choisir').style.display = 'block';
         document.getElementById('showNext').style.display = 'block';
         document.getElementById('item1').style.display = 'block';
         document.getElementById('selected').value = 1;
         document.getElementById('selectedprest').value = 0;


     });


         $("#showNext").click(function() {
             document.getElementById('selectedprest').value = 0;

             var selected = document.getElementById('selected').value;
         var total = document.getElementById('total').value;
         //     alert(selected);
         //    alert(total);
         var next = parseInt(selected) + 1;
         document.getElementById('selected').value = next;

         if ((selected == 0)) {
             document.getElementById('termine').style.display = 'none';
             document.getElementById('item1').style.display = 'block';
             document.getElementById('choisir').style.display = 'block';

             //document.getElementById('selected').value=1;
             // $("#selected").val('1');

         }

         if ((selected) == (total  )) {//alert("Il n y'a plus de prestataires, Réessayez");
             document.getElementById('termine').style.display = 'block';

             document.getElementById('item'+(selected)).style.display = 'none';
             document.getElementById('showNext').style.display = 'none';
             document.getElementById('choisir').style.display = 'none';


         } else {

         if ((selected != 0) && (selected <= total + 1)) {
             document.getElementById('choisir').style.display = 'block';
             document.getElementById('termine').style.display = 'none';
             document.getElementById('item' + selected).style.display = 'none';
             document.getElementById('item' + next).style.display = 'block';


             $("#selected").val(next);



         }
     }

         if(next>parseInt(total)+1) {
             document.getElementById('item' + selected).style.display = 'none';
         }


     });



 }); // $ function
