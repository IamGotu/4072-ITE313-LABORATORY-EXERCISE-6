// server.js
const express = require('express');
const path = require('path');
const session = require('express-session');
const bodyParser = require('body-parser');
const passport = require('passport');
const passportConfig = require('./passportConfig');
const authRoutes = require('./routes/auth'); // Adjust path if necessary
const profileRoutes = require('./routes/profile'); // Adjust path if necessary
const mysql = require('mysql');
require('dotenv').config(); // Load environment variables

const app = express();

// Database connection
const db = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
});

db.connect((err) => {
    if (err) {
        console.error('Database connection error:', err);
        throw err;
    }
    console.log('Connected to the database');
});

// Middleware
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// Session middleware configuration
app.use(session({
    secret: process.env.SESSION_SECRET,
    resave: false,
    saveUninitialized: true,
    cookie: { secure: false } // For development only. Use true with HTTPS in production
}));

// Passport middleware
app.use(passport.initialize());
app.use(passport.session());
passportConfig(passport);

// Authentication routes
app.use('/auth', authRoutes);

// User profile routes
app.use('/profile', profileRoutes); // Ensure this line is present

// Serve static files from the 'public' directory
app.use(express.static(path.join(__dirname, 'public')));

// Catch-all route for serving the AngularJS application
app.get('*', (req, res) => {
    res.sendFile(path.join(__dirname, 'public/index.html'));
});

// Start the server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});
