
@extends('layouts.mainlayout')

@section('content')
  <?php

  echo "
                       <script> window.showAlert = function(){
                            alertify.alert('<a href=`#`>Voir notification</a>');
                        }

//works with modeless too
            alertify.alert().setting('modal', false);
             window.showAlert();
     //   });
     </script>
";
    ?>

    @endsection