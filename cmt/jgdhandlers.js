(function($) 
    {
        // Create your own jQuery function. First define "jQuery.fn" and add to this object your function 
        // name see below. (jQuery.fn is a prototype of jQuery object) 
        $.fn.commentSystem = function(link){
            // We can ... select an element on the page and insert it into another: If an element selected this way 
            // is inserted into a single location elsewhere in the DOM, it will be moved into the target (not cloned) 
            // and a new set consisting of the inserted element is returned:
            
            // Presumably as the call was: $(".Form").commentSystem(e.currentTarget);
            // we're appending the element .Form to the DIV containing the link and calling show on that Form. This places
            // it just below the Reply link position clicked by the user.
            $(this).appendTo(link.parentElement).show();
        }
    }(jQuery)
);


// To run code as soon as the document is ready to be manipulated, jQuery has the .ready statement. This doesn't 
// wait until all images are finished downloading, including banner ads are loaded as does window.onload.  
$(document).ready( 
    // Anonymous function, presumably doesn't need a name as its only called by the .ready event.
    function(){
        // Setup event handlers            
        hideForm();
        onNewReplyLinkClick();
        onNewCommentLinkClick();
    }
);


// This function will hide the form
function hideForm(){
    $(".Form").hide();
}


// This function will create the link
function createLink(){           
    $(".cmt-cnt").append("<div class=replylink><a href=#>Reply</a></div>");   
}


// This function will execute when the link in objects of 'replylink' class are clicked and execute the form on that node index
// This make use of the the selected click event bubbling up to document level where it is handled. This allows not only 'replylink'
// clicks from the original html but also new 'replylink's added to new comments by javascript/jquery.
function onNewReplyLinkClick()
{
    // Attach a handler function to the 'replylink' class click events:
    $(document).on('click', 'a.replylink', function(e){
            // e is the short var reference for event object which will be passed to event handlers.

            // data-id is assigned by the server to identify a comment when replying.
            var commentId  = this.attributes["data-id"].value;
                       
            // $( ... ): wrap something up and make a jQuery object out of it - often assigned to a var for easy use.
            // .index: take a DOM node and returns an index.
            // Returns an integer indicating the position of the passed element i.e. this, relative to the original collection.
            
            // Put the Comment ID in a hidden input field in the form so it's passed to the server with the rest of the reply.
            $(".Form input[name='idreplyto']").val( commentId );
            
            // Put the subject from the comment into the subject of the reply, heading H3 containing subject if sirst child
            var subject = this.parentNode.children[0].innerText; 
            $(".Form input[name='subject']").val(subject);
                        
            // Call the declared custom function on the Form element, presumably making Form 'this'?
            // e.currentTarget is the current DOM element within the event bubbling phase which will,   
            // typically be equal to the this of the function
            $(".Form").commentSystem(e.currentTarget);
            
            // Load form with any cached data
            if(name = getCookie("name")){
                if(name=="unknown") name = "";
                document.getElementById("name").value = name;
            }
            if(email = getCookie("email"))      document.getElementById("email").value = email;
            if(comment = getCookie("comment"))  document.getElementById("comment").value = comment;
            
            // Position cursor in first field of newly positioned and displayed form
            $(".Form input[name='name']").focus();   
            
            // If it's not visible scroll it into view
            if (!$(".Form").is(":hidden")) {
                // Scroll the form into view
                if(jQuery(".Form").position()){
                    if(jQuery(".Form").position().top < jQuery(window).scrollTop()){
                        //scroll up
                        //jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top}, 1000);
                        jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(".Form").height() + 15}, 1000);
                    }
                    else if(jQuery(".Form").position().top + jQuery(".Form").height() > jQuery(window).scrollTop() + (window.innerHeight || document.documentElement.clientHeight)){
                        //scroll down
                        jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(".Form").height() + 15}, 1000);
                    }
                }
            }
                     
            // Prevent default handler for clicking a link to '#' which moves to top of page
            return false;
        }
    );
}

function onNewCommentLinkClick(){
        // jQuery provides a set of Event Helper Functions which can be used either to trigger an event [or] to bind 
        // any event type.
        // .click: Bind an event handler to the “click” JavaScript event, or trigger that event on an element.
        // Attach a function to the click event:
    $('.new-com-bt').click(
        // e is the short var reference for event object which will be passed to event handlers.
        function(e){
			$(this).hide();
            // data-id is assigned by the server to identify a comment when replying.
            var commentId  = "0";
                       
            // $( ... ): wrap something up and make a jQuery object out of it - often assigned to a var for easy use.
            // .index: take a DOM node and returns an index.
            // Returns an integer indicating the position of the passed element i.e. this, relative to the original collection.
            
            // Put the Comment ID in a hidden input field in the form so it's passed to the server with the rest of the reply.
            $(".Form input[name='idreplyto']").val( commentId );
            
            // Call the declared custom function on the Form element, presumably making Form 'this'?
            // e.currentTarget is the current DOM element within the event bubbling phase which will,   
            // typically be equal to the this of the function
            $(".Form").commentSystem(e.currentTarget);
            
            // Load form with any cached data
            if(name = getCookie("name")){
                if(name=="unknown") name = "";
                document.getElementById("name").value = name;
            }
            if(email = getCookie("email"))      document.getElementById("email").value = email;
            if(subject = getCookie("subject"))  document.getElementById("subject").value = subject;
            if(comment = getCookie("comment"))  document.getElementById("comment").value = comment;
            
            // Position cursor in first field of newly positined and displayed form
            $(".Form input[name='name']").focus();   
            
            // If it's not visible scroll it into view
            if (!$(".Form").is(":hidden")) {
                // Scroll the form into view
                if(jQuery(".Form").position()){
                    if(jQuery(".Form").position().top < jQuery(window).scrollTop()){
                        //scroll up
                        //jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top}, 1000);
                        jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(".Form").height() + 15}, 1000);
                    }
                    else if(jQuery(".Form").position().top + jQuery(".Form").height() > jQuery(window).scrollTop() + (window.innerHeight || document.documentElement.clientHeight)){
                        //scroll down
                        jQuery('html,body').animate({scrollTop:jQuery(".Form").position().top - (window.innerHeight || document.documentElement.clientHeight) + jQuery(".Form").height() + 15}, 1000);
                    }
                }
            }
                     
            // Prevent default handler for clicking a link to '#' which moves to top of page
            return false;
        }
    );
}

$(function() {
    $.ajaxSetup({
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });
});

$('.bt-cancel-com, #bt-cancel-com').click(function(){
	$('.the-new-com').val('');
	$('.new-com-cnt').fadeOut('fast', function(){
		$('.new-com-bt').fadeIn('fast');
	});
});
//$(".bt-add-com").prop('disabled',true);
$(".bt-add-com").click(function(){
    var targetPage = "comments_add.php";
    var url = $(this).attr("target_path")+targetPage; // the script to which the form data is sent for processing and saving

    $(".Form input[type='text']").css("border","");
    $.ajax({
       type: "POST",
       url: url,
       data: $("#f1").serialize(), // serializes the form's elements.
       success: function(data){
            if(validate(data, targetPage)){
               // Is is a comment? ...
    		   if(data.indexOf("level0") > -1){
    			     $('#cmt-container').prepend(data);
    		   }else{
    				var dataArr = data.split('data-id="');
    				var dataid = dataArr[1].split('"')[0];
    				// The data-id in a Reply refers to the Id of the Comment its a reply to.
    				$(data).insertAfter($('#' + dataid));
    		   }
                          
               // Whatever it is, even an error, hide the form again.
               hideForm();
              
               // Not sure if this is needed?
               $('#response').empty();
           }
       }
    });

    return false; // avoid to execute the actual submit of the form.
});

function validate(data, target){
    if(data.indexOf(target)==0&&data.match(/name/i)){
        return errorElement("name");
    }else if(data.match(/email/i)){
        return errorElement("email");        
    }else if(data.indexOf(target)==0&&data.match(/subject/i)){
        return errorElement("subject");
    }else if(data.indexOf(target)==0&&data.match(/comment/i)){
        return errorElement("comment");
    }
    

    return true;
}

function errorElement(elm){
    $(".Form input[name='"+elm+"']").focus();
    $(".Form input[name='"+elm+"']").css("border","1px solid red");
    return false;
}

// Key down and key up handlers from http://www.javascripter.net/faq/keyboardinputfiltering.htm 

    function inputDigitsOnly(e){
        var chrTyped, chrCode=0, 
        evt=e?e:event;
        if (evt.charCode!=null)     
            chrCode = evt.charCode;
        else if (evt.which!=null)   
            chrCode = evt.which;
        else if (evt.keyCode!=null) 
            chrCode = evt.keyCode;

        if (chrCode==0) 
            chrTyped = 'SPECIAL KEY';
        else 
            chrTyped = String.fromCharCode(chrCode);

        //[test only:] display chrTyped on the status bar 
        self.status='inputDigitsOnly:chrTyped = '+chrTyped;

        //Digits, special keys & backspace [\b] work as usual:
         if (chrTyped.match(/[a-zA-Z0-9\.\s,':;!\?]|[\b]|SPECIAL/)) 
            return true;
         if (evt.altKey || evt.ctrlKey || chrCode<28) 
            return true;

         //Any other input? Prevent the default response:
         if (evt.preventDefault) 
            evt.preventDefault();
         evt.returnValue=false;
         return false;
    }
    
    var today = new Date();
    var expiry = new Date(today.getTime() + 10 * 24 * 3600 * 1000); // plus 10 days

    function setCookie(name, value)
    {
        document.cookie=name + "=" + escape(value) + "; path=/; expires=" + expiry.toGMTString();
    }
    
    function getCookie(name)
    {
        var re = new RegExp(name + "=([^;]+)");
        var value = re.exec(document.cookie);
        return (value != null) ? unescape(value[1]) : null;
    }

    function validateForm(){alert("Validating ..");
        var name    =    document.getElementById("name");
        var email   =    document.getElementById("email");
        var subject =    document.getElementById("subject");
        var comment =    document.getElementById("comment");
        var idreplyto =  document.getElementById("idreplyto");
        var pageid  =    document.getElementById("pageid");
        
        if(idreplyto == null){
            alert("idreplyto is null");
        }
        
        if(pageid == null){
            alert("pageid is null");
        }
        
        // Store before filter ing so user isn't annoyed to see what, if anything, is missing
        setCookie("name",    name.value);
        setCookie("email",   email.value);
        setCookie("subject", subject.value);
        setCookie("comment", comment.value);
        setCookie("idreplyto", idreplyto.value);
                
        var reName = /[^a-zA-Z\s']/g;
        var newName    = name.value.replace(reName," ");
        document.getElementById("name").value = newName;
        
        var reSubject = /[^a-zA-Z0-9\.\s,':;!\?]/g;
        var newSubject = subject.value.replace(reSubject," "); 
        document.getElementById("subject").value = newSubject;
        
        var reComment = /[^a-zA-Z0-9\.\s,':;!\?]/g;
        var newComment = comment.value.replace(reComment," "); 
        document.getElementById("comment").value = newComment;
        
        var reReplyTo = /[^0-9]/g;
        var newReplyTo = idreplyto.value.replace(reReplyTo," "); 
        document.getElementById("idreplyto").value = newReplyTo;
        
        var rePageId = /[^0-9]/g;
        var newPageId = idreplyto.value.replace(rePageId," "); 
        document.getElementById("pageid").value = newPageId;
        
        return true;
    }

//---------------------------------------------
function addEventHandler(elem,eventType,handler){
    if (elem.addEventListener)
    {
        // Registers the specified listener on the 'EventTarget' it's called on.  
        elem.addEventListener (eventType,handler,false);
    }
    else if (elem.attachEvent) 
        elem.attachEvent ('on'+eventType,handler); 
    else return 0;
    return 1;
}

// onload: Call the init() function to add event handlers!
function init(){
    // Element[4] is the textarea in the form
    addEventHandler(self.document.f1.elements[4],'keypress',inputDigitsOnly);
}

