import { z } from "zod";
import { login } from "../services/auth.service.js";
import { asyncHandler } from "../utils/asyncHandler.js";

const loginSchema = z.object({
  email: z.string().email(),
  password: z.string().min(6),
});

export const loginController = asyncHandler(async (req, res) => {
  const credentials = loginSchema.parse(req.body);
  const session = await login(credentials);

  if (!session) {
    return res.status(401).json({ message: "Credenciales invalidas" });
  }

  res.json(session);
});

export const meController = asyncHandler(async (req, res) => {
  res.json({ user: req.user });
});
