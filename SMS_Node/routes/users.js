var express = require('express');

var db = require('../config/dbconnection'); //reference of dbconnection.js  
var router = express.Router();

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.send('respond with a resource');
});



router.get('/createsessionid', function(req, res, next) 
{
	
	var request = require('request');
	request('https://telenorcsms.com.pk:27677/corporate_sms2/api/ping.jsp?session_id=a941650bfdd944eab2d4ddc10f0f381c', function (error, response, body) {
	if (!error && response.statusCode == 200) {
		console.log(body) // Print the google web page.
		 res.send(body);
	 }
	})
	
  
});



function updateSocketId(id,from)
{
  // console.log(id+" connected "+from);
   try{
			var coloms =[];
			var tem={};
			var tem2={};
			tem.id=id;
			tem2.id=id;
			
			if(from)
			tem.from=from;
			
			coloms[0]=tem;
			coloms[1]=tem2;
			// console.log(coloms);
			var queryString = "UPDATE `agent` SET ? WHERE  ?";
								
			var query = db.query({
			sql: queryString,
			timeout: 10000,
			}, coloms);
			
			//console.log(query.sql)
			
			query.on('error', function(err) {
			if (err) 
			{
				err.status = 503;
				err.data=null;
				//next(err);
				//console.log(err);
			} 
			})
			.on('result', function(row)
			 {
				var err = new Error('Upadte');
				err.status = 200;
				err.data=null;
				//next(err);
				//console.log(err);
			});
			 
	   }
	catch(ex){
			
			var err = new Error(ex);
			err.status = 205;
			err.data=null;
			//next(err);
			//console.log(err);
		}
		
	
}


function removeSocketId(id)
{
	// console.log(id+" disc connected ");
     try{
			var coloms =[];
			var tem={};
			var tem2={};
			tem.from=null;
			tem2.id=id;
			
			/*if(socket_id)
			tem.socket_id=socket_id;
            */			
			coloms[0]=tem;
			coloms[1]=tem2;
			// console.log(coloms);
			var queryString = "UPDATE `agent` SET ? WHERE  ?";
								
			var query = db.query({
			sql: queryString,
			timeout: 10000,
			}, coloms);
			
			//console.log(query.sql)
			query
			.on('error', function(err) {
			if (err) 
			{
				err.status = 503;
				err.data=null;
				//next(err);
				//console.log(err);
			} 
			})
			.on('result', function(row)
			 {
				var err = new Error('Upadte');
				err.status = 200;
				err.data=null;
				//next(err);
				//console.log(err);
			});
			 
	   }
	catch(ex){
			
			var err = new Error(ex);
			err.status = 205;
			err.data=null;
			//next(err);
			//console.log(err);
		}
		
	
}


router.updateSocketId=updateSocketId;
router.removeSocketId=removeSocketId;

module.exports = router;
