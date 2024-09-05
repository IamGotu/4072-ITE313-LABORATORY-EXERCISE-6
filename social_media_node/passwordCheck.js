const bcrypt = require('bcryptjs');

// Replace these with the password you're testing and the hashed password from your database
const password = 'yourpassword'; // Password you are trying to login with
const hashedPassword = '$2a$10$OMjLWwr51IMLi5pboSPb8uk3a4ODoawiV9NY6aet7Ym9NQNmwEbna'; // Password hash from DB

bcrypt.compare(password, hashedPassword, (err, isMatch) => {
    if (err) throw err;
    console.log('Password match:', isMatch); // Should be true if passwords match
});
