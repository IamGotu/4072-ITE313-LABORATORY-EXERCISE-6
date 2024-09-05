const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const passport = require('passport');
const db = require('../config/db'); // Ensure this path is correct

// Registration endpoint
router.post('/register', (req, res) => {
    const { name, email, password } = req.body;

    if (!name || !email || !password) {
        return res.status(400).json({ success: false, message: 'All fields are required.' });
    }

    db.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
        if (err) {
            console.error('Database query error:', err);
            return res.status(500).json({ success: false, message: 'Database error.' });
        }
        if (results.length > 0) {
            return res.status(400).json({ success: false, message: 'Email already exists.' });
        }

        bcrypt.hash(password, 10, (err, hashedPassword) => {
            if (err) {
                console.error('Error hashing password:', err);
                return res.status(500).json({ success: false, message: 'Error hashing password.' });
            }

            db.query('INSERT INTO users (name, email, password) VALUES (?, ?, ?)', [name, email, hashedPassword], (err) => {
                if (err) {
                    console.error('Database insertion error:', err);
                    return res.status(500).json({ success: false, message: 'Database error.' });
                }
                res.json({ success: true, message: 'Registration successful. Please log in.' });
            });
        });
    });
});

// Login endpoint
router.post('/login', (req, res, next) => {
    passport.authenticate('local', (err, user, info) => {
        if (err) {
            console.error('Error during authentication:', err);
            return res.status(500).json({ success: false, message: 'Error during authentication.' });
        }
        if (!user) {
            console.error('Authentication failed:', info.message);
            return res.status(401).json({ success: false, message: info.message });
        }
        req.logIn(user, (err) => {
            if (err) {
                console.error('Error during login:', err);
                return res.status(500).json({ success: false, message: 'Error logging in.' });
            }
            res.json({ success: true, message: 'Login successful' });
        });
    })(req, res, next);
});

// Logout endpoint
router.post('/logout', (req, res) => {
    req.logout((err) => {
        if (err) {
            console.error('Error during logout:', err);
            return res.status(500).json({ success: false, message: 'Error during logout.' });
        }
        req.session.destroy((err) => {
            if (err) {
                console.error('Error destroying session:', err);
                return res.status(500).json({ success: false, message: 'Error destroying session.' });
            }
            res.json({ success: true, message: 'Logout successful' });
        });
    });
});

module.exports = router;
