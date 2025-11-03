const express = require("express");
const router = express.Router();

const userRoutes = require("./users");
const productRoutes = require("./products");

router.use("/users", userRoutes);
router.use("/products", productRoutes);
router.get()

module.exports = router;
// Compare this snippet from src/routes/index.js:
// const express = require("express");
// const router = express.Router();
