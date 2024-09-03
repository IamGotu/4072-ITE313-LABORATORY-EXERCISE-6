const db = require('../../config/db');

exports.register = (req, res) => {
    const { username, email, password } = req.body;
    const query = 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)';
    db.query(query, [username, email, password], (err, result) => {
        if (err) throw err;
        res.json({ message: 'User registered successfully!' });
    });
};

exports.login = (req, res) => {
    const { username, password } = req.body;
    const query = 'SELECT * FROM users WHERE username = ? AND password = ?';
    db.query(query, [username, password], (err, result) => {
        if (err) throw err;
        if (result.length > 0) {
            res.json({ message: 'Login successful!' });
        } else {
            res.status(401).json({ message: 'Invalid credentials!' });
        }
    });
};

exports.profile = (req, res) => {
    const username = 'some_username'; // Replace with session username
    const query = 'SELECT username, email FROM users WHERE username = ?';
    db.query(query, [username], (err, result) => {
        if (err) throw err;
        res.json(result[0]);
    });
};
