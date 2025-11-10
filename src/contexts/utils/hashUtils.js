const bcrypt = require("bcryptjs");

const SALT_ROUNDS = 10;

async function hashWachtwoord(plainWachtwoord) {
  try {
    return await bcrypt.hash(plainWachtwoord, SALT_ROUNDS);
  } catch (error) {
    throw new Error("Fout bij het hashen van wachtwoord");
  }
}

async function verifieerWachtwoord(plainWachtwoord, hashedWachtwoord) {
  try {
    return await bcrypt.compare(plainWachtwoord, hashedWachtwoord);
  } catch (error) {
    throw new Error("Fout bij het verifiëren van wachtwoord");
  }
}

module.exports = {
  hashWachtwoord,
  verifieerWachtwoord,
};
