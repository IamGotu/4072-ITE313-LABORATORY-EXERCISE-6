
const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const app = express();

const authRoutes = require('./routes/authRoutes');

app.use(bodyParser.json());
app.use('/api', authRoutes);

app.listen(3000, () => {
    console.log('Server is running on port 3000');
});
