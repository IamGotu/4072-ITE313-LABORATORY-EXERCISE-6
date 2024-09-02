const express = require('express');
const cors = require('cors');
const app = express();
const port = 3000;
const mongoose = require('mongoose');
app.use(cors()); // Enable CORS
app.get('/', (req, res) => {
  res.send('Hello from Express!');
});
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
mongoose.connect('mongodb://localhost:27017/mydb', {
    useNewUrlParser: true,
    useUnifiedTopology: true
  }).then(() => {
    console.log('Connected to MongoDB');
  }).catch((error) => {
    console.error('MongoDB connection error:', error);
  });