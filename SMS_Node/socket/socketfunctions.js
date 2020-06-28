var express = require('express');
var router = express.Router();
var users = require('../routes/users');

function socketfun(io)
{
	io.use(function(socket, next)
	{
		var socketjson=socket.handshake.query;
		socket.agentid = socketjson.id;
		socket.fromphone = socketjson.from;
		if(socket.agentid && socket.fromphone)
		{
		    next();
		    console.log('A user connected ');
		}
		 else
		 {
			 console.log('A user not  connected due to missing params'); 
		 }
	});
	 
	io.on('connection', function(socket)
	{
		 users.updateSocketId(socket.agentid,socket.fromphone);
		 
		 socket.on('disconnect',function()
			{
				users.removeSocketId(socket.agentid); 
				socket=null;
				delete socket; 
				
			});
	});
	
}


module.exports = socketfun;
