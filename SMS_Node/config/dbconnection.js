
var mysql = require('mysql');
var conn = mysql.createPool({  
    host: 'localhost',  
    user: 'root',  
    password: '',  
    database: 'sms'  
});  

module.exports = conn;  