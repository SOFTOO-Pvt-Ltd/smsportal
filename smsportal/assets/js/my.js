


var url = window.location.pathname;
	var filename = url.substring(url.lastIndexOf('/')+1);

$(document).ready(function () {
	
	
 if(filename =="advancesearch" )
 {	
 
	if($('#agemulids').val())
	{
		
		var agemulids=$('#agemulids').val();
		var dataarray=agemulids.split(",");
		$('.selectpicker').selectpicker('val', dataarray);
		$('.selectpicker').selectpicker('render');

	}
	
                    
 }
	
  //called when key is pressed in textbox
  $("#username").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#errmsg").html("Digits Only").show().fadeOut("slow");
              // return false;
     }
	 else
	 {
		$('.userAlert').hide(); 
		$('.passwordAlert').hide(); 
		
	 }
	 
	 $('.userAlert').hide(); 
		$('.passwordAlert').hide(); 
   });
   
   
	$( ".loginForm" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		 event.preventDefault();
	});
	
	
	
	$('#adsearch').on('submit', function(e) {
		//e.preventDefault()
		var val = $('#cuisine-select').val().join(',')
		
		$('#agentid_a').val(val);
	
	});
	
	
	
	$( "#broadcasts" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		event.preventDefault();
		broadcastmsg();
	});

	// trigger to check if send later clicked on broadcaste
	$( "#send_ltr" ).on('click',function( event ) {
		event.preventDefault();

		broadcastLtrmsg();
	});
	
	
	
	$( "#incomingOutgoingSms" ).submit(function( event ) {
		//alert( "Handler for .submit() called." );
		event.preventDefault();
		replay();
	});
	
	
	
	
	$("form#broadcasts input:radio").change(function () {
	
		
		$('.groupscc').hide();
		  $('.texscc').hide();  
        if ($(this).val() == "1") {
        	$('option', $('#groupd')).each(function(element) {
		    	$(this).removeAttr('selected').prop('selected', false);
		  	});
		  	$("#groupd").multiselect('refresh');
		  	$('#total_recep').text('0 member');
			$('#commasepratedn').val('');
			$('#commasepratedn').text('');
			$('.addNumbers').hide();
			$('.selectWrapper').show();
		  
        
		//alert(1);
	    
    } else {
	    
    	$('option', $('#groupd')).each(function(element) {
	    	$(this).removeAttr('selected').prop('selected', false);
	  	});
	  	$("#groupd").multiselect('refresh');
	  	$('#total_recep').text('0 member');
       	$('.selectWrapper').hide();
	   	$('.addNumbers').show();
        // alert(2);
    }
});


$("form#setreplyy input:radio").change(function () {
	
		
		 $('.setreplayid1').hide();
		  
			if ($(this).val() == "1") {
				$("#setreplayid").prop("disabled", false);
			
			} else {
			
			$("#setreplayid").prop("disabled", true);
			}
});



$('.phone').mask('03999999999', {placeholder:''});


   
});


$(document).keypress(function(e) {
	
	var url = window.location.pathname;
	var filename = url.substring(url.lastIndexOf('/')+1);
		
		if(e.which == 13) 
		{
			if(filename =="index.php" ||   filename =='')
			 {
				 login();
			 }
			 
			 if(filename =="broadcast" )
			 {
				 searchrbr();
			 }
			 
			 if(filename =="sms" || filename =="search")
			 {
				 searchr();
			 }
		}
	
});


$("#tex").keypress(function (e) {
	//alert('dd');
	
	var val=$('#tex').val().length;
	$('#chcount').html('');
	totalMessage = parseInt(val) / parseInt(160);
	reminderVal = parseInt(val) % 160;
	if (reminderVal == 0) {
		val = 0;
	}
	$('#chcount').html(parseInt(totalMessage)+1 + '/' + reminderVal);
 	
});


$("#tex").keyup(function (e) {
	//alert('dd');
	
	var val=$('#tex').val().length;
	$('#chcount').html('');
	totalMessage = parseInt(val) / parseInt(160);
	reminderVal = parseInt(val) % parseInt(160);
	if (reminderVal == 0) {
		val = 0;
	}
	$('#chcount').html(parseInt(totalMessage)+1 + '/' + reminderVal);
 	
});






//   LIGIN FUNCTION  //

function login()
{

	if(trim($("#username").val()) == ""){ $('.userAlert').show();	$("#username").focus();return false;	}
	if(trim($("#password").val()) == ""){ $('.passwordAlert').show();	$("#password").focus();return false;	}
	var username = $("#username").val();
	var password = $("#password").val();
		
		
	//$(".overlayloading").show();
	$( ".btn-lightgreen" ).addClass( "loading" );
    $.ajax({

    url : '../api/login',
    type : 'POST',
    data : {
        'methodName' : 'login',
		'username' : username,
		'password' : password
    },
    dataType:'json',
    success : function(data) { 
	$( ".btn-lightgreen" ).removeClass( "loading" );
		if(data.status=='200')
		{
			  window.location.href = 'sms.php';
		}
		else
		{
			 $('.alert-dismissable').show();
		}
    },
    error : function(request,error)
    {
		$( ".btn-lightgreen" ).removeClass( "loading" );    
    }
});
}



function logout()
{

	//$(".overlayloading").show();
	$.ajax({
	
		url : '../api/logout',
		type : 'POST',
		data : {
			'methodName' : 'signout',
		},
		dataType:'json',
		success : function(data) {
			$(".overlayloading").hide();              
			  window.location.reload(true);
			},
		error : function(request,error)
		{
		  $(".overlayloading").hide();   
		 window.location.reload(true); 
		}
	});

}


if(filename=='sms')
  setInterval(function(){ if(idofagent !='')getpending() }, 3000);	 
  
  
  if(filename=='detail')
  setInterval(function(){  if(idofagent2 !='')getpending2() }, 3000);


function getpending()
{
//alert(idofagent);
	$.ajax({
	
		url : '../api/pending',
		type : 'GET',
		data : {
			'id' :idofagent,
		},
		dataType:'json',
		timeout: 2700,
		success : function(data) 
		   {
			   //alert(data['data']);
				if(data['data'])  
				{ 
				    servers2 = data['data'];
					$.each(servers2, function(index, value) 
			        {
						if(value.pending)
						{
							 $('.btn-red').text(value.pending);
				             $('.btn-red').show();
						}
						else
						{
							$('.btn-red').text('');
				             $('.btn-red').hide();
						}
						
						if(value.from)
						{
							$("."+value.from).closest('tr').css("background-color", "transparent");
							$("."+value.from).css("font-weight", "100");
							$("."+value.from).closest('tr').prop('title', 'Locked by another agent');
							$("#id_"+value.from).html(value.id);
							$("."+value.from).children('a').removeAttr("href"); //replaces 'href' value to empty string
						}
						else
						{
							
							$(".dataTable a").each(function() {
								
								if($(this).attr('href') === undefined) 
								{
								    var linkhref='detail?id='+$(this).attr('class');
									$("."+$(this).attr('class')).closest('tr').prop('title', 'Click to check the availablility');
									$("."+$(this).attr('class')).prop("href", linkhref)
								}
							});
							
							
						}
						
					});
				
				}
				else
				{
				  $('.btn-red').text('');
				  $('.btn-red').hide();
				}
			
				
			  
			},
		error : function(request,error)
		{
		      $('.btn-red').hide();
		}
	});

}



function getpending2()
{

	$.ajax({
	
		url : '../api/pending2',
		type : 'GET',
		data : {
			'id' :idofagent2,
			'from' :frompho,
		},
		dataType:'json',
		timeout: 2700,
		success : function(data) 
		   {
			
			if(data['data'])  
			{ 
			
			servers = data['data'];
			$i=0;
			$.each(servers, function(index, value) 
			{
				 idofagent2=value.maxid;
				if(value.send=='0')
				{
					if( $i==0)
					{
						$("#maxicsmstxt").text('');
						$("#maxicsmstxt").text(value.text);
					}
				    $('.smsContentArea').prepend('\
				    <div class="smsUnit driver">\
					 <span class="sender">Recipient to Agent ('+value.datatime+')</span>\
					 <div class="sms">'+value.text+'</div>\
					</div>\
					');
				}
				else
				{
					
					 $('.smsContentArea').prepend('\
						<div class="smsUnit agent">\
						 <span class="sender">Agent to Recipient ('+value.datatime+')</span>\
						 <div class="sms">'+value.text+'</div>\
						</div>\
					');
				}
				
			
			 $i++;	
			   
			});
			
			}
				
				 
				
			},
		error : function(request,error,data)
		{
			
		 //alert(data);
		}
	});

}






function addnew()

{
	
	if(trim($("#addphonenumber").val()) == ""){ $("#addphonenumber").focus();return false;	}
	var string=$('#addphonenumber').val();
	
	string = string.replace(/^.{1}/g, '92');
	//alert(trim($("#commasepratedn").val()));
	
	if(trim($("#commasepratedn").val()) != "")
	{
		
		var oldstring= $('#commasepratedn').val();
		string=oldstring+','+string;
		$('#total_recep').text(string.split(',').length + ' members');
		$('#commasepratedn').val(string);
	    $('#addphonenumber').val('');
	}
	else
	{
	   	$('#commasepratedn').val('');
	   	$('#commasepratedn').val(string);
	   	$('#addphonenumber').val('');
        $('#total_recep').text('1 member');
	}
}


function broadcastmsg()
{
	var group_id = '';
	var commaseparatedn = '';
	var text = '';
	
	 var selValue = $('input[name=list-radio]:checked').val(); 
	
	if(selValue=='1')
	{
		if(trim($("#groupd").val()) == "")
		{
		   $('.groupscc').show();	$("#groupd").focus();return false;
		}
	}
	
	if(selValue=='2')
	{
		if(trim($("#commasepratedn").val()) == "")
		{
		   $('.texscc').show();	$("#commasepratedn").focus();return false;
		}
	}
	
	
	
	//if(trim($("#commasepratedn").val()) == ""){ $('.texscc').show();	$("#commasepratedn").focus();return false;	}
	if(trim($("#tex").val()) == ""){ $('.texsp').show();	$("#tex").focus();return false;	}
	 group_id = $("#groupd").val();
	 commaseparatedn = $("#commasepratedn").val();
	 text = $("#tex").val();
	
	
	
		
	//$(".overlayloading").show();
	$( ".btn-lightgreen" ).addClass( "loading" );
	
	$.ajax({
	
		url : '../api/broadcast',
		type : 'POST',
		data : {
			'group_id' :group_id,
			'commaseparatedn' :commaseparatedn,
			'text' :text
		},
		//dataType:'json',
		success : function(data) 
		    {
			  $("#forid002").prop("checked", true).trigger("click");
			  $('.selectWrapper').hide();
		      $('.addNumbers').show();
			  $( ".btn-lightgreen" ).removeClass( "loading" );
			  $('.bclooo').trigger('click');
			  $("#groupd").val('');
			  $("#commasepratedn").val('');
			  $("#tex").val("");
			  $('.texscc').hide();
			  $('.texsp').hide();
			},
		error : function(request,error)
		{
			
			  $("#forid002").prop("checked", true).trigger("click");
			  $('.selectWrapper').hide();
		      $('.addNumbers').show();
		      $( ".btn-lightgreen" ).removeClass( "loading" );
			   $('.bclooo').trigger('click');
			  $("#groupd").val('');
			   $('#commasepratedn').val('');
			   $("#tex").val("");
			  $('.texscc').hide();
			  $('.texsp').hide();
			 
			  
		}
	});
	
}

// ***************** Later braodcast  messages code start here ***************
// Function to check broadcast later messages and it will check if user entered date and time if yes it will submit form


function broadcastLtrmsg()
{
	var group_id = '';
	var commaseparatedn = '';
	var text = '';
	
	 var selValue = $('input[name=list-radio]:checked').val(); 
	var datetimeval = $('#ltr_datetime').val();

	if(selValue=='1')
	{
		if(trim($("#groupd").val()) == "")
		{
		   $('.groupscc').show();	$("#groupd").focus();return false;
		}
	}
	
	if(selValue=='2')
	{
		if(trim($("#commasepratedn").val()) == "")
		{
		   $('.texscc').show();	$("#commasepratedn").focus();return false;
		}
	}

	if (datetimeval == '' || typeof(datetimeval) == 'undefined') {
		$('.ltr_datetime').show();	$("#ltr_datetime").focus(); return false;
	} else {
		$('.ltr_datetime').hide();
	}
	
	
	
	//if(trim($("#commasepratedn").val()) == ""){ $('.texscc').show();	$("#commasepratedn").focus();return false;	}
	if(trim($("#tex").val()) == ""){ $('.texsp').show();	$("#tex").focus();return false;	}
	 group_id = $("#groupd").val();
	 commaseparatedn = $("#commasepratedn").val();
	 text = $("#tex").val();
	
	
	
		
	//$(".overlayloading").show();
	$( ".btn-lightgreen" ).addClass( "loading" );
	$( "#send_ltr" ).addClass( "loading" );
	
	$.ajax({
	
		url : '../api/broadcastltr',
		type : 'POST',
		data : {
			'group_id' :group_id,
			'commaseparatedn' :commaseparatedn,
			'text' :text,
			'datetime' : datetimeval
		},
		//dataType:'json',
		success : function(data) 
		    {
			  $("#forid002").prop("checked", true).trigger("click");
			  $('.selectWrapper').hide();
		      $('.addNumbers').show();
			  $( ".btn-lightgreen" ).removeClass( "loading" );
			  $( "#send_ltr" ).removeClass( "loading" );
			  $('.bclooo').trigger('click');
			  $("#groupd").val('');
			  $("#commasepratedn").val('');
			  $("#tex").val("");
			  $('.texscc').hide();
			  $('.texsp').hide();
			},
		error : function(request,error)
		{
			
			  $("#forid002").prop("checked", true).trigger("click");
			  $('.selectWrapper').hide();
		      $('.addNumbers').show();
		      $( ".btn-lightgreen" ).removeClass( "loading" );
			   $('.bclooo').trigger('click');
			  $("#groupd").val('');
			   $('#commasepratedn').val('');
			   $("#tex").val("");
			  $('.texscc').hide();
			  $('.texsp').hide();
			 
			  
		}
	});
	
}


// *************  Later boradcast messages code ended here ************

function ConfirmDelete(id)
{
	
  var x = confirm("Are you sure you want to delete?");
  if (x)
  {
	 
	 $.ajax({
	
		url : '../api/groupdelete',
		type : 'POST',
		data : {
			'id' :id,
		},
		dataType:'json',
		success : function(data) 
		   {
			   
				window.location.reload(true); 
			  
			},
		error : function(request,error)
		{
		  window.location.reload(true);
		}
	});
	 
	 
      return true;
  }
  else
  {
    return false;
  }
}


function ConfirmagentDelete(id,active)
{
var activetag='Deactivate';	
if(active=='0')
var activetag='Activate';	
		
  var x = confirm("Are you sure you want to "+activetag+"?");
  if (x)
  {
	 
	 $.ajax({
	
		url : '../api/ConfirmagentDelete',
		type : 'POST',
		data : {
			'id' :id,
			'active' :active,
		},
		dataType:'json',
		success : function(data) 
		   {
				window.location.reload(true); 
			  
			},
		error : function(request,error)
		{
		window.location.reload(true);
		}
	});
	 
	 
      return true;
  }
  else
  {
    return false;
  }
}


function agentedit(id)
{
	$("#agentid").val(id);
	$("#password1").val('');
	
	$("#name1").val($("#agentname_"+id).val());
	$("#upsubmit1").val("Update Agent");
	$(".roletype").hide();
}

function createag()
{
	$("#agentid").val('');
	$("#name1").val('');
	$("#password1").val('');
	$("#upsubmit1").val("Create Agent");
	$(".roletype").show();

}

function replay()
{
	 var idofagent2='';
	
	if(trim($("#ougoing").val()) == "")
		{
		   $('.userAlert').show();	$("#ougoing").focus();return false;
		}
		
		$( ".btn-lightgreen" ).addClass( "loading" );
		
		
		var    agentid = $("#agentid").val();
		var	parent_id = $("#parent_id").val();
		var	text = $("#ougoing").val();
		var	from = $("#from").val();
		var	operator = $("#operator").val();
	 
	 $.ajax({
	
		url : '../api/replay',
		type : 'POST',
		data : {
			'agentid' :agentid,
			'parent_id' :parent_id,
			'text' :text,
			'from' :from,
			'operator' :operator,
		},
		dataType:'json',
		success : function(data) 
		   {
			   $( ".btn-lightgreen" ).removeClass( "loading" );
			   $("#ougoing").val('');
			   $('.userAlert').hide();
				//window.location.reload(true); 
			  
			},
		error : function(request,error)
		{
		  $( ".btn-lightgreen" ).removeClass( "loading" );	
		   $("#ougoing").val('');
		   $('.userAlert').hide();
		  // window.location.reload(true);
		}
	});
	 

 
}









function submitFormd12()
{
	
	if(trim($("#name").val()) == "")
		{
		   $('.gname').show();	$("#name").focus();return false;
		}
		
		
		if(trim($("#file").val()) == "" && trim($('#pname').val()) == '' && trim($('#pphone').val()) == '')
		{
		   $("#file").focus();return false;
		}
		return true;
	
}


function submitFormd121()
{
	
	if(trim($("#name1").val()) == ""){ $('.userAlert1').show();	$("#name1").focus();return false;	}
	if(trim($("#password1").val()) == ""){ $('.passwordAlert1').show();	$("#password1").focus();return false;	}
	
	if(trim($("#password1").val()) != "")
	{
		if(trim($("#password2").val()) == "")
		{
		   $('.passwordAlert2').show();	$("#password2").focus();return false;
		}
		else if(trim($("#password1").val()) != trim($("#password2").val()))
		{
			$('.passwordAlert2').show();	$("#password2").focus();return false;
		}
		
	}
	
		return true;
	
}



function submitFormd122()
{
	
	if(trim($("#setreplayid").val()) == "")
		{
		   $('.setreplayid1').show();	$("#setreplayid").focus();return false;
		}
	
	if(trim($("#password1").val()) != "")
	{
		if(trim($("#password2").val()) == "")
		{
		   $('.passwordAlert2').show();	$("#password2").focus();return false;
		}
		else if(trim($("#password1").val()) != trim($("#password2").val()))
		{
			$('.passwordAlert2').show();	$("#password2").focus();return false;
		}
		
	}	
		
		return true;
	
}




function recipients(id)
{
	$("#appentabs tbody").html('');  
	var appendd=	'<tr><td ><div class="tdGroup"></div></td><td><div class="tdGroup">Loading ......</div></td></tr>';
	$("#appentabs tbody").append(appendd);
	 $.ajax({
	
		url : '../api/recipients',
		type : 'GET',
		data : {
			'id' :id,
		},
		dataType:'json',
		success : function(data) 
		   {
			 $("#appentabs tbody").html('');  
			  // alert(data.data);
				var myarray = data.data.split(',');
				for(var i = 0; i < myarray.length; i++)
				{
				    var appendd=	'<tr><td ><div class="tdGroup">'+(i + 1)+'</div></td><td><div class="tdGroup">'+myarray[i]+'</div></td></tr>';
					$("#appentabs tbody").append(appendd);
				}
				
			  
			},
		error : function(request,error)
		{
		 // $( ".btn-lightgreen" ).removeClass( "loading" );	
		
		}
	});
}



function searchr()
{
	   if(trim($("#search").val()) == "")
		{
		  $("#search").focus();return false;
		}
		else
		{
			var searchs=trim($("#search").val());
			window.location.href = 'search?search='+searchs;
		}
}



function searchrbr()
{
	   if(trim($("#search").val()) == "")
		{
		  $("#search").focus();return false;
		}
		else
		{
			var searchs=trim($("#search").val());
			window.location.href = 'broadcast?search='+searchs;
		}
}


//////////////////////////////Trim function below here/////////////////////
//////////////////////////////Trim function below here/////////////////////
//////////////////////////////Trim function below here/////////////////////
//////////////////////////////Trim function below here/////////////////////
//////////////////////////////Trim function below here/////////////////////

function trim(str, chars) 
	{
		if ($.isArray(str)) {
			return str;
		}
		return ltrim(rtrim(str, chars), chars);
	}
function ltrim(str, chars) 
	{
		chars = chars || "\\s";
		return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
	}
function rtrim(str, chars) 
	{
		chars = chars || "\\s";
		return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
	}
function IsAllSpaces(myStr)
	{
		while (myStr.substring(0,1) == " ")
		{
			myStr = myStr.substring(1, myStr.length);
		}
		if (myStr == "")
		{
			return true;
		}
		return false;
}







////////////////////////////////////////////////////////