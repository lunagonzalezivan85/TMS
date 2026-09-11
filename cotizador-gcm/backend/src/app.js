import express from "express";
import cors from "cors";
import helmet from "helmet";
import morgan from "morgan";
import { env } from "./config/env.js";
import { authRouter } from "./routes/auth.routes.js";
import { quotesRouter } from "./routes/quotes.routes.js";
import { dashboardRouter } from "./routes/dashboard.routes.js";
import { errorHandler } from "./middlewares/error.middleware.js";

export const app = express();

app.use(helmet());
app.use(cors({ origin: env.corsOrigin }));
app.use(express.json({ limit: "1mb" }));
app.use(morgan(env.nodeEnv === "production" ? "combined" : "dev"));

app.get("/health", (req, res) => {
  res.json({ status: "ok", service: "cotizador-gcm-api" });
});

app.use("/api/auth", authRouter);
app.use("/api/quotes", quotesRouter);
app.use("/api/dashboard", dashboardRouter);

app.use(errorHandler);
