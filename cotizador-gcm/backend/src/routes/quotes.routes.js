import { Router } from "express";
import {
  createQuote,
  deleteQuote,
  exportQuotePdf,
  getQuote,
  listQuotes,
  updateQuote,
} from "../controllers/quotes.controller.js";
import { requireAuth } from "../middlewares/auth.middleware.js";

export const quotesRouter = Router();

quotesRouter.use(requireAuth);
quotesRouter.get("/", listQuotes);
quotesRouter.get("/:id", getQuote);
quotesRouter.post("/", createQuote);
quotesRouter.put("/:id", updateQuote);
quotesRouter.delete("/:id", deleteQuote);
quotesRouter.post("/export-pdf", exportQuotePdf);
