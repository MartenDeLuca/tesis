var express = require('express');
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var clients = [];
var activos = [];
let room;
var maximos = 0;

//var sql = require('mssql');
/*var config = {
	user: 'sa',
	password: '123',
	server: '127.0.0.1', 
	database: 'crmflow',
	port: 1433
}*/

function filtrarPorRoom(obj) {
	if (obj.room === room) {
		return true;
	} else {
		return false;
	}
}


server.listen(3000);

io.on("connection", function(socket){	
	console.log("alguien se conecto");
	room = socket.handshake.query['token'];
	socket.join(room);	
	
	socket.on("disconnect", function(data){
		for( var i=0, len=clients.length; i<len; ++i ){
                var c = clients[i];
                if(c.clientId == socket.id){
                	socket.to(room).emit("quitar-usuario", c.customId);	
                    clients.splice(i,1);
                    break;
            }
        }	
        var len = activos.length;

        for( var i=0; i<len; ++i ){
            var c = activos[i];
            if(c.socketId == socket.id){
            	activos.splice(i,1);
            }
        }	     
	});	
	
	/* con broadcast va a todos menos el usuario que lo envio el socket */
	socket.on("agregar-lista", function(data){							
		socket.to(room).emit("agregar-lista", data);		
	});	

	socket.on("ingreso", function(data){			
		var arrayPorRoom = clients.filter(filtrarPorRoom);
		io.sockets.connected[socket.id].emit("agregar-usuarios-al-conectar", arrayPorRoom);
		socket.to(room).emit("ingreso", data.customId);						
		var clientInfo = new Object();
        clientInfo.customId = data.customId;
        clientInfo.clientId = socket.id;
        clientInfo.room = room;        
        clients.push(clientInfo);		
	});	

	socket.on("cambiar-nombre-lista", function(data){					
		socket.to(room).emit("cambiar-nombre-lista", data);			
	});	
	socket.on("eliminar-lista", function(data){					
		socket.to(room).emit("eliminar-lista", data);			
	});	
	socket.on("eliminar-todas-tarjetas-lista", function(data){					
		socket.to(room).emit("eliminar-todas-tarjetas-lista", data);			
	});		
	socket.on("copiar-lista", function(data){					
		socket.to(room).emit("agregar-lista", data);			
	});
	socket.on("mover-lista", function(data){					
		socket.to(room).emit("mover-lista", data);			
	});	
	socket.on("mover-todas-tarjetas-lista", function(data){					
		socket.to(room).emit("mover-todas-tarjetas-lista", data);			
	});		
	socket.on("ordenar-fecha-vencimiento", function(data){					
		socket.to(room).emit("ordenar-fecha-vencimiento", data);			
	});		
	socket.on("ordenar-fecha-creacion-asc", function(data){					
		socket.to(room).emit("ordenar-fecha-creacion-asc", data);			
	});	
	socket.on("ordenar-fecha-creacion-desc", function(data){					
		socket.to(room).emit("ordenar-fecha-creacion-desc", data);			
	});			
	socket.on("agregar-tarjeta", function(data){					
		socket.to(room).emit("agregar-tarjeta", data);			
	});	
	socket.on("modificar-tarjeta", function(data){					
		socket.to(room).emit("modificar-tarjeta", data);			
	});	
	socket.on("eliminar-tarjeta", function(data){					
		socket.to(room).emit("eliminar-tarjeta", data);		
	});
	socket.on("copiar-tarjeta", function(data){					
		socket.to(room).emit("copiar-tarjeta", data);				
	});	
	socket.on("mover-tarjeta", function(data){					
		socket.to(room).emit("mover-tarjeta", data);				
	});
	socket.on("mover-tarjeta-dragula", function(data){					
		socket.to(room).emit("mover-tarjeta-dragula", data);				
	});		

	/*
	socket.on("concurrencia", function(){	
		socket.emit("concurrencia", activos);		
	});	

	socket.on("activo", function(data){
		console.log(data);
		const DbConnectionString = 'mssql://sa:123@martinpc:1433/crmflow';
		sql.on('error', err => {
			console.dir(err);
			sql.close();
		});
		sql.connect(DbConnectionString).then(pool => {
			return pool.request()
				.query('select id_usuario from crm_usuarios where id_usuario > 10');
		}).then(result => {
			sql.close();
			maximos = result.recordset[0].id_usuario;
		}).catch(err => {
			console.dir(err);
			sql.close();
		});
		
		var len = activos.length;	
		
		if (len < maximos){
			for( var i=0; i<len; ++i ){
	            var c = activos[i];
	            var ok=true;
	           
	            if(c.ip == data.ip){
	            	ok=false;
	            	break;
	            }
	        }	
			if (ok || len == 0){
				
				var usuario=new Object();		
				usuario.ip   = data.ip;
				//usuario.user= data.usuario;
				//usuario.tiempo = data.tiempo;
		        usuario.socketId   = socket.id;
		        activos.push(usuario);		   
		    }
		    console.log("CONECTAR HAY ACTIVOS: "+len);
	    } else {	    
	    	socket.emit("activo");
	    }
	});	*/	
});
