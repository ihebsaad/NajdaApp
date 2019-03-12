<?php
/*
 * File: folder_structure.blade.php
 * Category: View
 * Author: M.Goldenbaum
 * Created: 15.09.18 19:53
 * Updated: -
 *
 * Description:
 *  -
 */
/**
 * @var \Webklex\IMAP\Support\FolderCollection $paginator
 * @var \Webklex\IMAP\Folder $oFolder
 */
?>

<script>
function enableactivate  (id){

var setting = {
"async": true,
"crossDomain": true,
"url": appurl+"admin/enableactivate/"+id,
"method": "GET",
"headers": {
'Access-Control-Allow-Origin': '*'
},
"processData": false

}


$.ajax(setting).done(function (response) {
    /*
$("#pinenabled").slideDown();
//refresh PINs list
$http.get(newURL+'pins').success(function (responsepins) {
$scope.DataPins = responsepins ;
console.log('success enable');
});*/

});

$.ajax(setting).fail(function (response) {
console.log('fail enable  '+ response);
});


}
</script>