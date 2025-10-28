const express = require("express");
const app = express();
const routes = require("./routes");

app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Gebruik alle routes met /api prefix
app.use("/api", routes);

module.exports = app;
