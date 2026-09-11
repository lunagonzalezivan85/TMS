import { Router } from "express";
import { query } from "../config/db.js";
import { requireAuth } from "../middlewares/auth.middleware.js";
import { asyncHandler } from "../utils/asyncHandler.js";

export const dashboardRouter = Router();

dashboardRouter.get(
  "/summary",
  requireAuth,
  asyncHandler(async (req, res) => {
    const result = await query(
      `SELECT
        COUNT(*)::int AS total_quotes,
        COALESCE(SUM(final_price), 0)::numeric AS total_quoted,
        COALESCE(AVG(margin_percent), 0)::numeric AS average_margin
       FROM quotes`
    );

    res.json({ summary: result.rows[0] });
  })
);
