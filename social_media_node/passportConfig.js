// passportConfig.js
const LocalStrategy = require('passport-local').Strategy;
const bcrypt = require('bcryptjs');
const db = require('./config/db'); // Ensure this path is correct

module.exports = function(passport) {
    passport.use(new LocalStrategy(
        { usernameField: 'email', passwordField: 'password' },
        (email, password, done) => {
            db.query('SELECT * FROM users WHERE email = ?', [email], (err, results) => {
                if (err) {
                    return done(err);
                }
                if (results.length === 0) {
                    return done(null, false, { message: 'No user with that email' });
                }
                
                const user = results[0];
                bcrypt.compare(password, user.password, (err, isMatch) => {
                    if (err) {
                        return done(err);
                    }
                    if (isMatch) {
                        return done(null, user);
                    } else {
                        return done(null, false, { message: 'Incorrect password' });
                    }
                });
            });
        }
    ));

    passport.serializeUser((user, done) => {
        done(null, user.id);
    });

    passport.deserializeUser((id, done) => {
        db.query('SELECT * FROM users WHERE id = ?', [id], (err, results) => {
            if (err) {
                return done(err);
            }
            done(null, results[0]);
        });
    });
};
