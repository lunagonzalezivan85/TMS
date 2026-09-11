import bcrypt from "bcryptjs";
import { query } from "../config/db.js";
import { signToken } from "../utils/jwt.js";

export async function login({ email, password }) {
  const result = await query(
    "SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = $1",
    [email.toLowerCase()]
  );

  const user = result.rows[0];
  if (!user || !user.is_active) return null;

  const valid = await bcrypt.compare(password, user.password_hash);
  if (!valid) return null;

  const safeUser = {
    id: user.id,
    name: user.name,
    email: user.email,
    role: user.role,
  };

  return { user: safeUser, token: signToken(safeUser) };
}
