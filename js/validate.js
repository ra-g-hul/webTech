function checkname()
    {
	
	   var uname=document.getElementById( "name" ).value;
	
	   if(uname)
	   {
	        $.ajax({
			   type: 'post',
			   url: 'checkdata.php',
			   data: {
			   name:uname,
			   },
			   success: function (response) {
			   $( '#name_status' ).html(response);
  		          if(response=="OK")	
                  {
                     return true;	
                  }
                  else
                  {
                     return false;	
                  }
                }
		      });
	
	   }
	   else
	   {
		   $( '#name_status' ).html("");
		   return false;
	   }
	}

    function checkemail()
    {
	
	   var uemail=document.getElementById( "emailSignup" ).value;
	
	   if(uemail)
	   {
	       $.ajax({
			   type: 'post',
			   url: 'checkdata.php',
			   data: {
			   email:uemail,
			   },
			   success: function (response) {
			   $( '#email_status' ).html(response);
		       if(response=="OK")	
               {
                  return true;	
               }
               else
               {
                  return false;	
               }
             }
		   });


	    }
	    else
	    {
		   $( '#email_status' ).html("");
		   return false;
	    }
	
	}


	function checkall()
	{
        var namehtml=document.getElementById("name_status").innerHTML;
        var emailhtml=document.getElementById("email_status").innerHTML;
       
	   if((namehtml && emailhtml)=="OK")
	   {
          return true;
	   }
	   else
	   {
          return false;
	   }
	}


