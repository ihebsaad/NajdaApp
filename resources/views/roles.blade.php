 
 <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<html>
<body>
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-6" style="padding:100px 50px 100px 50px">

<h2>Sélectionnez votre rôle pendant cette séance :</h2><br>

    <label class="radio">Agent
        <input type="radio" checked name="is_company">
        <span class="checkround"></span>
    </label>

    <label class="check ">Dispatcheur
        <input type="checkbox" checked="checked" name="is_name">
        <span class="checkmark"></span>
    </label>
    <label class="check ">Superviseur Médic
        <input type="checkbox"  name="is_name">
        <span class="checkmark"></span>
    </label>
    <label class="check ">Superviseur Technique
        <input type="checkbox"  name="is_name">
        <span class="checkmark"></span>
    </label>
    <label class="check ">Chargé Transport
        <input type="checkbox"  name="is_name">
        <span class="checkmark"></span>
    </label>
    <label class="check ">Dispatcheur Téléphonique
        <input type="checkbox"  name="is_name">
        <span class="checkmark"></span>
    </label>

<br>
 <button onclick="redirect()" class="btn cust-btn " type="button" id="btn-registration" style="font-size: 20PX;letter-spacing: 1px;width:150px">Entrer</button>
</div>

<div class="col-md-3">

</div>  
  
</div>
</body>
</html>
 <?php

 $urlapp=env('APP_URL');
 $urlapp='https://najdaapp.enterpriseesolutions.com/' ;
 if (App::environment('local')) {
     // The environment is local
     $urlapp='http://localhost/najdaapp';
 }

 ?>

 <script>
     function redirect()
     {
         window.location = '<?php echo $urlapp ; ?>';
     }
 </script>
<style>

/* The radio */
.radio {
 
     display: block;
    position: relative;
    padding-left: 30px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 20px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none
}

/* Hide the browser's default radio button */
.radio input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom radio button */
.checkround {

    position: absolute;
    top: 6px;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #fff ;
    border-color:#f8204f;
    border-style:solid;
    border-width:2px;
     border-radius: 50%;
}


/* When the radio button is checked, add a blue background */
.radio input:checked ~ .checkround {
    background-color: #fff;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkround:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */
.radio input:checked ~ .checkround:after {
    display: block;
}

/* Style the indicator (dot/circle) */
.radio .checkround:after {
     left: 2px;
    top: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background:#f8204f;
    
 
}

/* The check */
.check {
    display: block;
    position: relative;
    padding-left: 25px;
    margin-bottom: 12px;
    padding-right: 15px;
    cursor: pointer;
    font-size: 18px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.check input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 3px;
    left: 0;
    height: 18px;
    width: 18px;
    background-color: #fff ;
    border-color:#f8204f;
    border-style:solid;
    border-width:2px;
}



/* When the checkbox is checked, add a blue background */
.check input:checked ~ .checkmark {
    background-color: #fff  ;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.check input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.check .checkmark:after {
    left: 5px;
    top: 1px;
    width: 5px;
    height: 10px;
    border: solid ;
    border-color:#f8204f;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.cust-btn{
	margin-bottom: 10px;
	background-color: #f8204f;
	border-width: 2px;
	border-color: #f8204f;
	color: #fff;
}
.cust-btn:hover{
	
	border-color: #f8204f;
	background-color: #fff;
	color: #f8204f;
	border-radius: 20px;
	transform-style: 2s;

}


</style>
 
 
