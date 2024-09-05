const bcrypt = require('bcryptjs');
const password = '123'; // Use the password used for registration
const hashedPasswordFromDb = '$2a$10$x0ZBdwqnsDRPkp3yHJbNYefpYOpJBrfZJxxO9/Qa296fyYGRyPv8C'; // Hash from DB

bcrypt.compare(password, hashedPasswordFromDb, (err, isMatch) => {
    if (err) throw err;
    console.log('Password match:', isMatch); // Should be true if passwords match
});
