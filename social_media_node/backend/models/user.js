const db = require('../util/database');

module.export = class User {
    constructor(name, email, password){

        this.name = name;
        this.email = email;
        this.password = password;
    }

    static find(email){
        return db.execute('SELECT * FROM users WHERE email = ?', [email]
        );
    }

    static save(user){
        return db.execute(
            'INSERT INTO users (name, email, password) VALUE (?, ?, ?)', [user.name, user.email, user.password]
        );
    }
};

