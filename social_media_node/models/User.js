const db = require('../db');

const User = {
    findByEmail: function(email, callback) {
        db.query('SELECT * FROM users WHERE email = ?', [email], callback);
    },
    // Add other methods as needed
};

module.exports = User;
