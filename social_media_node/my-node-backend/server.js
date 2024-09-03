const express = require('express');
const cors = require('cors');
const mongoose = require('mongoose');
const taskRoutes = require('./routes/tasks');
const app = express();
const port = 3000;
mongoose.connect('mongodb://localhost:27017/mydb', {
  useNewUrlParser: true,
  useUnifiedTopology: true
}).then(() => {
  console.log('Connected to MongoDB');
}).catch((error) => {
  console.error('MongoDB connection error:', error);
});
app.use(cors());
app.use(express.json());
app.use('/tasks', taskRoutes);
app.listen(port, () => {
  console.log(`Server running on http://localhost:${port}`);
});
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ message: 'Something went wrong!' });
});