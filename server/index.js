const express = require('express');
const app = express();
const port = 8888;

app.get('/', (req, res) => {
  res.send('Hello World!');
});

const { createConnection } = require('mysql2');

// Create a connection pool
const conn = new createConnection({
    user: 'root',
    host: 'localhost',
    database: 'shopping',
    password: '',
    port: 3306,
});
conn.connect(function(err) {
  if (err) {
    console.error('Error connecting to database:', err.stack);
    return;
  }
  console.log('Connected to database successfully');
});


app.listen(port, () => {
  console.log(`App listening at http://localhost:${port}`);
});
