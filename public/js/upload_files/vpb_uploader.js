
khaled=[];

kf=0;
nump=0;
ii=0;
   
function vpb_multiple_file_uploader(vpb_configuration_settings)
{
	this.vpb_settings = vpb_configuration_settings;
	this.vpb_files = "";
	
	this.vpb_browsed_files = []
	var self = this;
	var vpb_msg = "Sorry, your browser does not support this application. Thank You!";
	
	//Get all browsed file extensions
	function vpb_file_ext(file) {
		return (/[.]/.exec(file)) ? /[^.]+$/.exec(file.toLowerCase()) : '';
	}
	
	/* Display added files which are ready for upload */
	//with their file types, names, size, date last modified along with an option to remove an unwanted file
	vpb_multiple_file_uploader.prototype.vpb_show_added_files = function(vpb_value)
	{
		this.vpb_files = vpb_value;
		//khaled=vpb_value;
		if(this.vpb_files.length > 0)
		{
			var vpb_added_files_displayer = vpb_file_id = "";
 			for(var i = 0; i<this.vpb_files.length; i++)
			{
				//Use the names of the files without their extensions as their ids
				var files_name_without_extensions = this.vpb_files[i].name.substr(0, this.vpb_files[i].name.lastIndexOf('.')) || this.vpb_files[i].name;
				vpb_file_id = files_name_without_extensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
				
				var vpb_file_to_add = vpb_file_ext(this.vpb_files[i].name);
				var vpb_class = $("#added_class").val();
				var vpb_file_icon;
				
				//Check and display File Size
				var vpb_fileSize = (this.vpb_files[i].size / 1024);
				//alert(vpb_fileSize);
				if (vpb_fileSize / 1024 > 1)
				{
					if (((vpb_fileSize / 1024) / 1024) > 1)
					{
						vpb_fileSize = (Math.round(((vpb_fileSize / 1024) / 1024) * 100) / 100);
						var vpb_actual_fileSize = vpb_fileSize + " Go";
					}
					else
					{
						vpb_fileSize = (Math.round((vpb_fileSize / 1024) * 100) / 100)
						var vpb_actual_fileSize = vpb_fileSize + " Mo";
					}
				}
				else
				{
					vpb_fileSize = (Math.round(vpb_fileSize * 100) / 100)
					var vpb_actual_fileSize = vpb_fileSize  + " Ko";
				}
				
				//Check and display the date that files were last modified
				var vpb_date_last_modified = new Date(this.vpb_files[i].lastModifiedDate);
				var dd = vpb_date_last_modified.getDate();
				var mm = vpb_date_last_modified.getMonth() + 1;
				var yyyy = vpb_date_last_modified.getFullYear();
				var vpb_date_last_modified_file = dd + '/' + mm + '/' + yyyy;
				
				//File Display Classes
				if( vpb_class == 'vpb_blue' ) { 
					var new_classc = 'vpb_white';
				} else {
					var new_classc = 'vpb_blue';
				}
				
				
				if(typeof this.vpb_files[i] != undefined && this.vpb_files[i].name != "")
				{
					//Check for the type of file browsed so as to represent each file with the appropriate file icon
					
					if( vpb_file_to_add == "jpg" || vpb_file_to_add == "JPG" || vpb_file_to_add == "jpeg" || vpb_file_to_add == "JPEG" || vpb_file_to_add == "gif" || vpb_file_to_add == "GIF" || vpb_file_to_add == "png" || vpb_file_to_add == "PNG" ) 
					{
						vpb_file_icon = '<img src="images/images_file.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "doc" || vpb_file_to_add == "docx" || vpb_file_to_add == "rtf" || vpb_file_to_add == "DOC" || vpb_file_to_add == "DOCX" )
					{
						vpb_file_icon = '<img src="images/doc.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "pdf" || vpb_file_to_add == "PDF" )
					{
						vpb_file_icon = '<img src="images/pdf.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "txt" || vpb_file_to_add == "TXT" || vpb_file_to_add == "RTF" )
					{
						vpb_file_icon = '<img src="images/txt.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "php" )
					{
						vpb_file_icon = '<img src="images/php.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "css" )
					{
						vpb_file_icon = '<img src="images/general.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "js" )
					{
						vpb_file_icon = '<img src="images/general.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "html" || vpb_file_to_add == "HTML" || vpb_file_to_add == "htm" || vpb_file_to_add == "HTM" )
					{
						vpb_file_icon = '<img src="images/html.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "setup" )
					{
						vpb_file_icon = '<img src="images/setup.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "video" )
					{
						vpb_file_icon = '<img src="images/video.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "real" )
					{
						vpb_file_icon = '<img src="images/real.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "psd" )
					{
						vpb_file_icon = '<img src="images/psd.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "fla" )
					{
						vpb_file_icon = '<img src="images/fla.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "xls" )
					{
						vpb_file_icon = '<img src="images/xls.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "swf" )
					{
						vpb_file_icon = '<img src="images/swf.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "eps" )
					{
						vpb_file_icon = '<img src="images/eps.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "exe" )
					{
						vpb_file_icon = '<img src="images/exe.gif" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "binary" )
					{
						vpb_file_icon = '<img src="images/binary.png" align="absmiddle" border="0" alt="" />';
					}
					else if( vpb_file_to_add == "zip" )
					{
						vpb_file_icon = '<img src="images/archive.png" align="absmiddle" border="0" alt="" />';
					}
					else
					{
						vpb_file_icon = '<img src="images/general.png" align="absmiddle" border="0" alt="" />';
					}
					var reader = new FileReader();
					nom_limit=this.vpb_files[i].name.substring(0, 40);
					tab=this.vpb_files[i];
					nomn=this.vpb_files[i].name;
					//urlk=this.vpb_files[i].mozFullPath;
					//alert(urlk);
					//Assign browsed files to a variable so as to later display them below
					//reader.onload = function(ee) {
					vpb_added_files_displayer += '<tr id="add_fileID'+vpb_file_id+'" class="'+new_classc+'"><td><a href="javascript:void(0)" id="file'+nomn+'" class="fileupkbs"  >'+' '+nom_limit+'</a></td><td>'+vpb_actual_fileSize+'</td><td><span id="remove'+vpb_file_id+'"><span style="color:red; cursor: pointer;" class="vpb_files_remove_left_inner" onclick="vpb_remove_this_file(\''+vpb_file_id+'\',\''+nomn+'\');">x</span></span></td></tr></div>';
					khaled.push(tab);
				    //alert(khaled[khaled.length-1].name);
				   //  }
				    // reader.readAsDataURL(this.vpb_files[i]);
				}
			}
			//Display browsed files on the screen to the user who wants to upload them
			$("#add_files").append(vpb_added_files_displayer);
			$("#added_class").val(new_classc);
			const k =document.querySelector('#vasplus_multiple_files');
			k.value="";
	        k.files= new FileListItem(khaled);
		   // k.value='';


		}

	  //$("#vasplus_multiple_files").val(null);
	}
	
	//File Reader
	vpb_multiple_file_uploader.prototype.vpb_read_file = function(vpb_e) {
		if(vpb_e.target.files) {
			self.vpb_show_added_files(vpb_e.target.files);
			self.vpb_browsed_files.push(vpb_e.target.files);
		} else {
			alert('Sorry, a file you have specified could not be read at the moment. Thank You!');
		}
	}
	
	
	function addEvent(type, el, fn){
	if (window.addEventListener){
		el.addEventListener(type, fn, false);
	} else if (window.attachEvent){
		var f = function(){
		  fn.call(el, window.event);
		};			
		el.attachEvent('on' + type, f)
	}
}

	
	//Get the ids of all added files and also start the upload when called
	vpb_multiple_file_uploader.prototype.vpb_starter = function() {
		if (window.File && window.FileReader && window.FileList && window.Blob) {		
			 var vpb_browsed_file_ids = $("#"+this.vpb_settings.vpb_form_id).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(vpb_browsed_file_ids).addEventListener("change", this.vpb_read_file, false);
			 document.getElementById(this.vpb_settings.vpb_form_id).addEventListener("submit", this.vpb_submit_added_files, true);
		} 
		else { alert(vpb_msg); }
	}
	
	//Call the uploading function when click on the upload button
	vpb_multiple_file_uploader.prototype.vpb_submit_added_files = function(){ self.vpb_upload_bgin(); }
	
	//Start uploads
	vpb_multiple_file_uploader.prototype.vpb_upload_bgin = function() {
		if(this.vpb_browsed_files.length > 0) {
			for(var k=0; k<this.vpb_browsed_files.length; k++){
				var file = this.vpb_browsed_files[k];
				this.vasPLUS(file,0);
			}
		}
	}
	
	//Main file uploader
	vpb_multiple_file_uploader.prototype.vasPLUS = function(file,file_counter)
	{
		if(typeof file[file_counter] != undefined && file[file_counter] != '')
		{
			//Use the file names without their extensions as their ids
			var files_name_without_extensions = file[file_counter].name.substr(0, file[file_counter].name.lastIndexOf('.')) || file[file_counter].name;
			var ids = files_name_without_extensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
			var vpb_browsed_file_ids = $("#"+this.vpb_settings.vpb_form_id).find("input[type='file']").eq(0).attr("id");
			
			var removed_file = $("#"+ids).val();
			
			if ( removed_file != "" && removed_file != undefined && removed_file == ids )
			{
				self.vasPLUS(file,file_counter+1);
			}
			else
			{
				var dataString = new FormData();
				dataString.append('upload_file',file[file_counter]);
				dataString.append('upload_file_ids',ids);
					
				$.ajax({
					type:"POST",
					url:this.vpb_settings.vpb_server_url,
					data:dataString,
					cache: false,
					contentType: false,
					processData: false,
					beforeSend: function() 
					{
						$("#uploading_"+ids).html('<div align="left"><img src="images/loadings.gif" width="80" align="absmiddle" title="Upload...."/></div>');
						$("#remove"+ids).html('<div align="center" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:blue;">Uploading...</div>');
					},
					success:function(response) 
					{
						setTimeout(function() {
							var response_brought = response.indexOf(ids);
							if ( response_brought != -1) {
								$("#uploading_"+ids).html('<div align="left" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:blue;">Completed</div>');
								$("#remove"+ids).html('<div align="center" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:gray;">Uploaded</div>');
							} else {
								var fileType_response_brought = response.indexOf('file_type_error');
								if ( fileType_response_brought != -1) {
									
									var filenamewithoutextension = response.replace('file_type_error&', '').substr(0, response.replace('file_type_error&', '').lastIndexOf('.')) || response.replace('file_type_error&', '');
									var fileID = filenamewithoutextension.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
									$("#uploading_"+fileID).html('<div align="left" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:red;">Invalid File</div>');
									$("#remove"+fileID).html('<div align="center" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:orange;">Cancelled</div>');
									
								} else {
									var filesize_response_brought = response.indexOf('file_size_error');
									if ( filesize_response_brought != -1) {
										var filenamewithoutextensions = response.replace('file_size_error&', '').substr(0, response.replace('file_size_error&', '').lastIndexOf('.')) || response.replace('file_size_error&', '');
										var fileID = filenamewithoutextensions.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
										$("#uploading_"+fileID).html('<div align="left" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:red;">Exceeded Size</div>');
										$("#remove"+fileID).html('<div align="center" style="font-family:Verdana, Geneva, sans-serif;font-size:11px;color:orange;">Cancelled</div>');
									} else {
										var general_response_brought = response.indexOf('general_system_error');
										if ( general_response_brought != -1) {
											alert('Sorry, the file was not uploaded...');
										}
										else { }
									}
								}
							}
							if (file_counter+1 < file.length ) {
								self.vasPLUS(file,file_counter+1); 
							} 
							else {}
						},2000);
					}
				});
			 }
		} 
		else { alert('Sorry, this system could not verify the identity of the file you were trying to upload at the moment. Thank You!'); }
	}
	this.vpb_starter();
}

function FileListItem(a) {
  a = [].slice.call(Array.isArray(a) ? a : arguments)
  for (var c, b = c = a.length, d = !0; b-- && d;) d = a[b] instanceof File
  if (!d) throw new TypeError("expected argument to FileList is File or array of File objects")
  for (b = (new ClipboardEvent("")).clipboardData || new DataTransfer; c--;) b.items.add(a[c])
  return b.files
}

function vpb_remove_this_file(id, filename)
{

/* if (window.File && window.FileReader && window.FileList && window.Blob) {
  alert(' Great success! All the File APIs are supported.');
} else {
  alert('The File APIs are not fully supported in this browser.');
}*/
	
		$("#vpb_removed_files").append('<input type="hidden" id="'+id+'" value="'+id+'">');
		//$("#add_fileID"+id).slideUp();
		jQuery(document).find("#add_fileID"+id).slideUp();
	

		for(var k=0; k<khaled.length; k++){

			if(khaled[k].name===filename)
			{
                khaled.splice(k,1);
                //alert(khaled[khaled.length-1].name);

			}
			
	     }

	     const kk =document.querySelector('#vasplus_multiple_files');
	     kk.value="";
	     kk.files= new FileListItem(khaled);
	
	return false;
}

// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';
 var nump=0;
 var pageNumber = 1;
 var thePDF = null;
  $(document).on('click','.fileupkbs', function() {


   var idfile=$(this).attr("id");
   idfile=idfile.substr(4);
   //alert(idfile);
     var file;
   for(var k=0; k<khaled.length; k++){

			if(khaled[k].name===idfile)
			{
               
               file = khaled[k];
			}
			
	     }


   
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			//var numPages = doc.numPages;
			loadingTask.promise.then(function(pdf) {
			kdelete =jQuery(document).find("#pdfbody");
			kdelete.empty();
			 // alert('PDF loaded');
			  //alert(pdf.numPages);
			  // Fetch the first page
              nump=pdf.numPages;
              
                thePDF=pdf; 
			  pageNumber = 1;
			  pdf.getPage(pageNumber).then( handlePages);

			
			$('#voirfichier').modal('show');
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}


  });

function handlePages (page) {
//alert('Page loaded');
//alert(kf);
var scale = 1.5;
var viewport = page.getViewport({scale: scale});
//jQuery("#pdfbody").append('<canvas id=pdfViewer'+kf+'></canvas>');
kapend =jQuery(document).find("#pdfbody");
kapend.append('<canvas id=pdfViewer'+pageNumber+'></canvas>');
// Prepare canvas using PDF page dimensions
var canvas = $("#pdfViewer"+pageNumber)[0];
//canvas = jQuery("#pdfbody").createElement( "canvas" );
var context = canvas.getContext('2d');
canvas.height = viewport.height;
canvas.width = viewport.width;

// Render PDF page into canvas context
var renderContext = {
  canvasContext: context,
  viewport: viewport
};
var renderTask = page.render(renderContext);
//jQuery("#pdfbody").appendChild( canvas );

 pageNumber++;
    if ( thePDF !== null && pageNumber <= nump )
    {
        thePDF.getPage( pageNumber ).then( handlePages );
    }
				


}