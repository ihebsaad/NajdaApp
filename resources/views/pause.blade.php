<head>
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
<meta charset="UTF-8">
<title>Najda Assistances - Pause</title>
<form class="container">
    {{ csrf_field() }}

    <br>
	          <?php $user = auth()->user();
?>
<H1 style="color:#0087dc; text-shadow:1px 1px white"> <?php echo $user->name.' '.$user->lastname;?> </h1><br>
<H1 style="color:white"> En Pause </h1><br>
  <input type="radio" id="init" name="control">
  <input type="radio" id="stop" name="control">
  <input type="radio" id="start" name="control"  checked="checked">
  <input type="reset" id="reset" name="control">
  <input type="checkbox" id="lap_1" name="lap">
  <input type="checkbox" id="lap_2" name="lap">
  <input type="checkbox" id="lap_3" name="lap">
  <input type="checkbox" id="lap_4" name="lap">

  <time><i></i><b></b><i></i></time>

  <div class="controls">
     <label for="start">Start</label> 
  </div>

 </form>

 <center><button  id="enpause" class="btn cust-btn " type="button"  style="cursor:pointer;margin-top:50px;font-size: 20px;letter-spacing: 2px;width:180px;color:white;border:none;border-radius:25px;background-color:#a0d468;padding:20px 20px 20px 20px">RETOUR</button></center>
<style>
html,
body {
  padding: 0;
  margin: 0;
  /*background-color: #0087dc;*/
  user-select: none;
    color: #888;
    text-shadow: 0 1px 0 rgba(0, 0, 0, .3);
    background: rgb(150, 150, 150);
    background: -moz-radial-gradient(center, ellipse cover, rgba(150, 150, 150, 1) 0%, rgba(89, 89, 89, 1) 100%);
    background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, rgba(150, 150, 150, 1)), color-stop(100%, rgba(89, 89, 89, 1)));
    background: -webkit-radial-gradient(center, ellipse cover, rgba(150, 150, 150, 1) 0%, rgba(89, 89, 89, 1) 100%);
    background: -o-radial-gradient(center, ellipse cover, rgba(150, 150, 150, 1) 0%, rgba(89, 89, 89, 1) 100%);
    background: -ms-radial-gradient(center, ellipse cover, rgba(150, 150, 150, 1) 0%, rgba(89, 89, 89, 1) 100%);
    background: radial-gradient(ellipse at center, rgba(150, 150, 150, 1) 0%, rgba(89, 89, 89, 1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '#969696', endColorstr = '#595959', GradientType = 1);

}

* {
  box-sizing: border-box;
}

input,
label {
  display: none;
}

.container {
font-family: "Comic Sans MS";
  font-weight: 300;
  margin: 60px auto 0;
  width: 90vw;
  min-width: 300px;
  max-width: 400px;
  text-align: center;
}

time {
  font-size: 68px;
  height: 1em;
  line-height: 1em;
  display: inline-block;
  overflow: hidden;
  animation-name: none;
  animation-play-state: paused;
  margin-bottom: 60px;
  color: #fff;
}

time i,
time b {
  float: left;
  font-style: normal;
  font-weight: 100;
  animation-name: inherit;
  animation-play-state: inherit;
}

.container > time b {
  height: 1em;
  min-width: 0.3em;
  padding-top: 0.3em;
}

.container > time b::before,
.container > time b::after {
  content: '';
  display: block;
  width: 0.08em;
  height: 0.08em;
  background-color: currentColor;
  border-radius: 100%;
  margin: 0 auto 0.29em;
}

time i::before,
time i::after {
  content: '0\A 1\A 2\A 3\A 4\A 5\A 6\A 7\A 8\A 9\A 0';
  white-space: pre;
  animation-name: inherit;
  animation-play-state: inherit;
  animation-iteration-count: infinite;
  animation-timing-function: steps(10);
  float: left;
  margin: 0 0.02em;
}

time i:first-child::before,
time i:nth-of-type(2)::before {
  content: '0\A 1\A 2\A 3\A 4\A 5\A 0';
  animation-timing-function: steps(6);
}

time i:first-child::before {
  animation-duration: 3600s;
}

time i:first-child::after {
  animation-duration: 600s;
}

time i:nth-of-type(2)::before {
  animation-duration: 60s;
}

time i:nth-of-type(2)::after {
  animation-duration: 10s;
}

time i:nth-of-type(3)::before {
  animation-duration: 1s;
}

time i:nth-of-type(3)::after {
  animation-duration: 100ms;
}

.controls {
  position: relative;
  height: 80px;
  margin-bottom: 20px;
}

.controls::before {
  display: none;
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 80px;
  height: 80px;
  background-color: #0F0F0F;
  opacity: 0.5;
  z-index: 10;
}

label {
  cursor: pointer;
  font-size: 16px;
  border: 2px solid #353535;
  background-color: #353535;
  box-shadow: inset 0 0 0 2px #0F0F0F;
  color: #ccc;
  width: 80px;
  border-radius: 100%;
  text-align: center;
  line-height: 76px;
  position: absolute;
  top: 0;
  left: 0;
}

label[for="start"] {
  background-color: #182E1C;
  border-color: #182E1C;
  color: #42CC57;
}

label[for="stop"] {
  background-color: #351614;
  border-color: #351614;
  color: #FF352C;
}

label[for="start"],
label[for="stop"] {
  right: 0;
  left: auto;
}

.laps {
  counter-reset: laps;
  list-style: none;
  margin: 0;
  padding-left: 0;
  border-top: 1px solid #333;
  font-size: 16px;
}

.laps li {
  color: #666;
  text-align: right;
  position: relative;
  border-bottom: 1px solid #333;
  padding-top: 1em;
  height: 3em;
}

.laps li::before {
  counter-increment: laps;
  content: 'Lap ' counter(laps);
  visibility: hidden;
  color: inherit;
  line-height: 3em;
  position: absolute;
  left: 0;
  top: 0;
}

.laps li time {
  visibility: hidden;
  color: inherit;
  font-size: inherit;
  margin-bottom: 0;
}

.laps li time i,
.laps li time b {
  font-weight: inherit;
  padding-top: 0;
}

.laps li time b::before {
  content: ':';
}

/* Visible control conditions */
#start:checked ~ .controls label[for="stop"],
#start:checked ~ #lap_1:not(:checked) ~ .controls label[for="lap_1"],
#start:checked ~ #lap_1:checked + #lap_2:not(:checked) ~ .controls label[for="lap_2"],
#start:checked ~ #lap_2:checked + #lap_3:not(:checked) ~ .controls label[for="lap_3"],
#start:checked ~ #lap_3:checked + #lap_4:not(:checked) ~ .controls label[for="lap_4"],
#start:checked ~ #lap_4:checked ~ .controls label[for="lap_4"],
#stop:checked ~ .controls label[for="start"],
#stop:checked ~ .controls label[for="reset"],
#init:checked ~ .controls label[for="start"],
#init:checked ~ .controls label[for="lap_1"] {
  display: block;
}

/* Disable lap control conditions */
#init:checked ~ .controls::before,
#start:checked ~ #lap_4:checked ~ .controls::before {
  display: block;
}


/* Visible lap conditions */
.laps li:first-child time,
.laps li:first-child::before,
#lap_1:checked ~ .laps li:nth-child(2) time,
#lap_1:checked ~ .laps li:nth-child(2)::before,
#lap_2:checked ~ .laps li:nth-child(3) time,
#lap_2:checked ~ .laps li:nth-child(3)::before,
#lap_3:checked ~ .laps li:nth-child(4) time,
#lap_3:checked ~ .laps li:nth-child(4)::before {
  visibility: visible;
}

/* Reset */
#init:checked ~ time,
#init:checked ~ .laps li time {
  animation-name: none;
}

#init:not(:checked) ~ time,
#init:not(:checked) ~ .laps li time {
  animation-name: digit;
}

/* Timer / Lap running conditions */
#start:checked ~ time,
#start:checked ~ #lap_1:not(:checked) ~ .laps li:first-child time,
#start:checked ~:checked + #lap_2:not(:checked) ~ .laps li:nth-child(2) time,
#start:checked ~:checked + #lap_3:not(:checked) ~ .laps li:nth-child(3) time,
#start:checked ~:checked + #lap_4:not(:checked) ~ .laps li:nth-child(4) time {
  animation-play-state: running;
}

/* Timer / Lap stopping conditions */
#stop:checked ~ time,
#stop:checked ~ .laps li time,
#start:checked ~ #lap_1:checked ~ .laps li:first-child time,
#start:checked ~ #lap_2:checked ~ .laps li:nth-child(2) time,
#start:checked ~ #lap_3:checked ~ .laps li:nth-child(3) time,
#start:checked ~ #lap_4:checked ~ .laps li:nth-child(4) time {
  color: #fff;
  animation-play-state: paused;
}

@keyframes digit {
  from {
    transform: translateY(0);
  }
  to {
    transform: translateY(calc(-100% + 1em));
  }
}
</style>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

    $(document).ready(function(){


        $('#enpause').click(function() {

         //   $("#dpause").css("display", "block");
          //  $("#enpause").css("display", "none");
            var _token = $('input[name="_token"]').val();
            // back statut en ligne
            $.ajax({
                url:"{{ route('users.changestatut') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(data){
                  window.location = '{{route('home')}}';

                }
            });
        });
		
     });
	 

</script>