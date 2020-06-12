var socket = io.connect("http://martinpc:3000", {"forceNew" : true});

socket.on('mensajes', function(data){
	mostrar(data);
});

function mostrar(data){
	var html = data.map(function(elem, index){
		return(`<div>
			<b>${elem.autor}</b>
			${elem.text}
		</div>`);
	}).join(" ");
				
	document.getElementById("mensajes").innerHTML = html;
}

function onMensaje(evento){
	var payload = {
		autor : document.getElementById("usuario").value,
		text : document.getElementById("texto").value
	};
	socket.emit("nuevo-mensaje", payload);
	return false;
}

socket.on( 'notificacion', function( data ) {
	//var actualuser = $( ".notification" ).attr("data-cliente");
	
	notifyMe(data.comentario,'https://pickaface.net/assets/images/slides/slide2.png',data.nombrUser,data.comentario);	
});

function notifyMe(theBody,theIcon,theTitle,theText) {
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
   notiIE(theIcon,theTitle,theText);
  }

  // Let's check whether notification permissions have already been granted
  else if (Notification.permission === "granted") {
    // If it's okay let's create a notification
    spawnNotification(theBody,theIcon,theTitle);
  }

  // Otherwise, we need to ask the user for permission
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
      // If the user accepts, let's create a notification
      if (permission === "granted") {
        var notification = new Notification("Hi there!");
      }
    });
  }

  // At last, if the user has denied notifications, and you 
  // want to be respectful there is no need to bother them any more.
}Notification.requestPermission().then(function(result) {
  console.log(result);
});

function spawnNotification(theBody,theIcon,theTitle) {
  var options = {
      body: theBody,
      icon: theIcon
  }
  var n = new Notification(theTitle,options);
}

function notiIE(image,theTitle,theText)
{
	   $.gritter.add({
                // (string | mandatory) the heading of the notification
                title: theTitle,
                // (string | mandatory) the text inside the notification
                text:theText,
                // (string | optional) the image to display on the left
                image: image,
                // (bool | optional) if you want it to fade out on its own or just sit there
                sticky: false,
                // (function) before the gritter notice is opened
                before_open: function(){
                    if($('.gritter-item-wrapper').length == 3)
                    {
                        // Returning false prevents a new gritter from opening
                        return false;
                    }
                }
		});
}