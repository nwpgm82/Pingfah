var mysql = require('mysql');

var mysql_conn = mysql.createConnection({
    host : "localhost",
    user : "root",
    password : "",
    database : "Pingfah"
});

mysql_conn.query('SELECT * FROM roomlist', function(err, rows) {
    if (err) {
        throw err;
    }
    for (id in rows) {
        console.log(rows[id]);
        
    }
});