/* eslint-disable react-hooks/exhaustive-deps, react-hooks/set-state-in-effect */
import { useEffect, useMemo, useState } from "react";
import "./App.css";
import {
  authApi,
  clearSession,
  dashboardApi,
  getStoredUser,
  quotesApi,
  setSession,
} from "./api/client";

const EXCHANGE_RATE = 36.6243;
const FUEL_PRICE_PER_GALLON = 149.3879;

const fuelProducts = [
  { id: "diesel", name: "Diesel", costPerGallon: 149.38 },
  { id: "maxxima_regular", name: "Maxxima Regular", costPerGallon: 161.08 },
  { id: "maxxima_premium", name: "Maxxima Premium", costPerGallon: 165.59 },
  { id: "jet_a1", name: "Jet A1", costPerGallon: 126.44 },
];

const fixedFactors = {
  laborIndex: 1.5,
  depreciationUsdPerKm: 0.081,
  maintenanceUsdPerKm: 0.13,
  tiresUsdPerKm: 0.05,
  municipalityPercent: 1,
  dgiPercent: 3,
};

const initialQuote = {
  productId: "diesel",
  customerName: "",
  customerRuc: "",
  customerPhone: "",
  volumeGallons: 3000,
  distanceKm: 262,
  fuelEfficiencyKmPerGallon: 10,
  marginPercent: 15,
  proposedPrice: "",
  driverPercent: 12,
  travelAllowance: 330,
  adminPercent: 8,
};

const initialFuelPricing = {
  referencePrice: 177.7815,
  discount: 8.3815,
  sellerCommission: 1.2,
};

function currency(value) {
  return new Intl.NumberFormat("es-NI", {
    style: "currency",
    currency: "NIO",
    maximumFractionDigits: 2,
  }).format(Number(value || 0));
}

function usd(value) {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
    maximumFractionDigits: 2,
  }).format(Number(value || 0));
}

function dualCurrency(value) {
  const cordobas = number(value);
  return `${currency(cordobas)} / ${usd(cordobas / EXCHANGE_RATE)}`;
}

function number(value) {
  return Number(value || 0);
}

function percent(value) {
  return number(value) / 100;
}

function getFuelProduct(productId) {
  return fuelProducts.find((product) => product.id === productId) || fuelProducts[0];
}

function parseCostBreakdown(value) {
  if (!value) return {};
  if (typeof value === "object") return value;

  try {
    return JSON.parse(value);
  } catch {
    return {};
  }
}

function Icon({ name }) {
  const paths = {
    cancel: "M18 6 6 18M6 6l12 12",
    delete: "M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14M10 11v5M14 11v5",
    edit: "M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z",
    more: "M12 8h.01M12 12h.01M12 16h.01",
    save: "M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2ZM17 21v-8H7v8M7 3v5h8",
    pdf: "M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8ZM14 2v6h6M8 16h1.5a1.5 1.5 0 0 0 0-3H8v5M13 13v5h1.5a2.5 2.5 0 0 0 0-5ZM17 13h3M17 16h2",
    view: "M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6ZM12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z",
    collapse: "M6 9l6 6 6-6",
  };

  return (
    <svg className="button-icon" viewBox="0 0 24 24" aria-hidden="true">
      <path d={paths[name]} />
    </svg>
  );
}

function calculateQuote(quote) {
  const volume = number(quote.volumeGallons);
  const distance = number(quote.distanceKm);
  const efficiency = number(quote.fuelEfficiencyKmPerGallon);
  const gallonsConsumed = efficiency > 0 ? distance / efficiency : 0;

  const fuelCost = gallonsConsumed * FUEL_PRICE_PER_GALLON;
  const depreciation = distance * fixedFactors.depreciationUsdPerKm * EXCHANGE_RATE;
  const maintenance = distance * fixedFactors.maintenanceUsdPerKm * EXCHANGE_RATE;
  const tires = distance * fixedFactors.tiresUsdPerKm * EXCHANGE_RATE;
  const fixedDirectCost = fuelCost + depreciation + maintenance + tires + number(quote.travelAllowance);
  const driverEffectivePercent = percent(quote.driverPercent) * fixedFactors.laborIndex;

  const retainedPercent =
    driverEffectivePercent +
    percent(quote.adminPercent) +
    percent(fixedFactors.municipalityPercent) +
    percent(fixedFactors.dgiPercent) +
    percent(quote.marginPercent);

  const denominator = Math.max(1 - retainedPercent, 0.01);
  const suggestedPrice = fixedDirectCost / denominator;
  const hasManualProposedPrice = number(quote.proposedPrice) > 0;
  const finalPrice = hasManualProposedPrice ? number(quote.proposedPrice) : suggestedPrice;
  const driverCost = finalPrice * driverEffectivePercent;
  const adminCost = finalPrice * percent(quote.adminPercent);
  const municipalityCost = finalPrice * percent(fixedFactors.municipalityPercent);
  const dgiCost = finalPrice * percent(fixedFactors.dgiPercent);
  const tripCost = fixedDirectCost + driverCost + adminCost + municipalityCost + dgiCost;
  const marginAmount = finalPrice - tripCost;
  const actualMarginPercent = finalPrice > 0 ? (marginAmount / finalPrice) * 100 : 0;
  const freightPerGallon = volume > 0 ? finalPrice / volume : 0;

  return {
    gallonsConsumed,
    fuelCost,
    depreciation,
    maintenance,
    tires,
    fixedDirectCost,
    driverEffectivePercent,
    driverCost,
    adminCost,
    municipalityCost,
    dgiCost,
    tripCost,
    marginAmount,
    suggestedPrice,
    hasManualProposedPrice,
    finalPrice,
    actualMarginPercent,
    freightPerGallon,
  };
}

function calculateFuelPricing(model, freightPerGallon) {
  const referencePrice = number(model.referencePrice);
  const discount = number(model.discount);
  const customerPrice = referencePrice - discount;
  const grossMargin = customerPrice - FUEL_PRICE_PER_GALLON;
  const netMargin = grossMargin - number(model.sellerCommission) - number(freightPerGallon);

  return {
    customerPrice,
    grossMargin,
    netMargin,
  };
}

function App() {
  const [user, setUser] = useState(() => getStoredUser());
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [showOperatingVars, setShowOperatingVars] = useState(true);
  const [credentials, setCredentials] = useState({
    email: "admin@gcmtransportes.com",
    password: "admin123",
  });
  const [quote, setQuote] = useState(initialQuote);
  const [fuelPricing, setFuelPricing] = useState(initialFuelPricing);
  const [quotes, setQuotes] = useState([]);
  const [selectedHistoryQuote, setSelectedHistoryQuote] = useState(null);
  const [editingQuoteId, setEditingQuoteId] = useState("");
  const [openActionMenuId, setOpenActionMenuId] = useState("");
  const [summary, setSummary] = useState({ total_quotes: 0, total_quoted: 0, average_margin: 0 });
  const [status, setStatus] = useState("");
  const [loading, setLoading] = useState(false);

  const selectedProduct = useMemo(() => getFuelProduct(quote.productId), [quote.productId]);
  const totals = useMemo(() => calculateQuote(quote), [quote]);
  const fuelTotals = useMemo(() => calculateFuelPricing(fuelPricing, totals.freightPerGallon), [fuelPricing, totals.freightPerGallon]);
  const selectedQuoteBreakdown = useMemo(
    () => parseCostBreakdown(selectedHistoryQuote?.cost_breakdown),
    [selectedHistoryQuote],
  );

  function handleApiError(error) {
    const message = error.message || "No se pudo completar la operacion.";

    if (message.toLowerCase().includes("token")) {
      clearSession();
      setUser(null);
      setQuotes([]);
      return "Sesion expirada. Inicie sesion nuevamente para guardar o generar PDF.";
    }

    return message;
  }

  async function loadPrivateData() {
    if (!user) return;
    try {
      const [quoteData, summaryData] = await Promise.all([
        quotesApi.list(),
        dashboardApi.summary(),
      ]);
      setQuotes(quoteData.quotes || []);
      setSummary(summaryData.summary || summary);
    } catch (error) {
      setStatus(handleApiError(error));
    }
  }

  useEffect(() => {
    loadPrivateData();
  }, [user]);

  function updateQuote(field, value) {
    setQuote((current) => ({
      ...current,
      [field]: value,
    }));
  }

  function updateFuelPricing(field, value) {
    setFuelPricing((current) => ({
      ...current,
      [field]: value,
    }));
  }

  function mapStoredQuoteToForm(storedQuote) {
    const breakdown = parseCostBreakdown(storedQuote.cost_breakdown);
    const fuelCustomerPrice = number(breakdown.fuelCustomerPricePerGallon);
    const storedDiscount = breakdown.fuelDiscount ?? 0;

    setQuote({
      ...initialQuote,
      productId: breakdown.productId || "diesel",
      customerName: storedQuote.customer_name || "",
      customerRuc: storedQuote.customer_ruc || "",
      customerPhone: storedQuote.customer_phone || "",
      volumeGallons: Number(storedQuote.volume_gallons || initialQuote.volumeGallons),
      distanceKm: Number(storedQuote.distance_km || initialQuote.distanceKm),
      fuelEfficiencyKmPerGallon: Number(storedQuote.fuel_efficiency_km_per_gallon || initialQuote.fuelEfficiencyKmPerGallon),
      marginPercent: Number(breakdown.marginTargetPercent ?? storedQuote.margin_percent ?? initialQuote.marginPercent),
      proposedPrice: Number(storedQuote.final_price || 0),
      driverPercent: Number(breakdown.driverPercent ?? initialQuote.driverPercent),
      travelAllowance: Number(breakdown.travelAllowance ?? initialQuote.travelAllowance),
      adminPercent: Number(breakdown.adminPercent ?? initialQuote.adminPercent),
    });

    setFuelPricing({
      referencePrice: Number(
        breakdown.fuelReferencePrice ??
        (fuelCustomerPrice > 0 ? fuelCustomerPrice + number(storedDiscount) : initialFuelPricing.referencePrice),
      ),
      discount: Number(storedDiscount),
      sellerCommission: Number(breakdown.sellerCommission ?? initialFuelPricing.sellerCommission),
    });
  }

  function cancelEditing() {
    setEditingQuoteId("");
    setQuote(initialQuote);
    setFuelPricing(initialFuelPricing);
    setStatus("Edicion cancelada.");
  }

  function validateQuoteBeforeExport() {
    if (!user) {
      setStatus("Inicie sesion para guardar o generar PDF.");
      return false;
    }

    if (quote.customerName.trim().length < 2) {
      setStatus("Ingrese el Nombre Cliente antes de guardar o generar PDF.");
      return false;
    }

    return true;
  }

  function buildQuotePayload() {
    return {
      customerName: quote.customerName.trim(),
      customerRuc: quote.customerRuc.trim(),
      customerPhone: quote.customerPhone.trim(),
      origin: `${number(quote.volumeGallons).toLocaleString("es-NI")} glns`,
      destination: `${number(quote.distanceKm).toLocaleString("es-NI")} km`,
      vehicleType: "Camion rigido",
      distanceKm: number(quote.distanceKm),
      baseCost: Number(totals.tripCost.toFixed(2)),
      marginPercent: Number(totals.actualMarginPercent.toFixed(2)),
      marginAmount: Number(totals.marginAmount.toFixed(2)),
      finalPrice: Number(totals.finalPrice.toFixed(2)),
      volumeGallons: number(quote.volumeGallons),
      fuelEfficiencyKmPerGallon: number(quote.fuelEfficiencyKmPerGallon),
      fuelPricePerGallon: FUEL_PRICE_PER_GALLON,
      freightPerGallon: Number(totals.freightPerGallon.toFixed(4)),
      costBreakdown: {
        productId: selectedProduct.id,
        productName: selectedProduct.name,
        productCostPerGallon: selectedProduct.costPerGallon,
        freightCalculationFuelCostPerGallon: FUEL_PRICE_PER_GALLON,
        fuelReferencePrice: number(fuelPricing.referencePrice),
        fuelDiscount: number(fuelPricing.discount),
        sellerCommission: number(fuelPricing.sellerCommission),
        fuelCustomerPricePerGallon: Number(fuelTotals.customerPrice.toFixed(4)),
        fuelCustomerTotal: Number((fuelTotals.customerPrice * number(quote.volumeGallons)).toFixed(2)),
        fuelCost: totals.fuelCost,
        gallonsConsumed: totals.gallonsConsumed,
        driverPercent: number(quote.driverPercent),
        laborIndex: fixedFactors.laborIndex,
        driverCost: totals.driverCost,
        depreciation: totals.depreciation,
        maintenance: totals.maintenance,
        tires: totals.tires,
        travelAllowance: number(quote.travelAllowance),
        adminPercent: number(quote.adminPercent),
        adminCost: totals.adminCost,
        municipalityCost: totals.municipalityCost,
        dgiCost: totals.dgiCost,
        fixedDirectCost: totals.fixedDirectCost,
        tripCost: totals.tripCost,
        marginAmount: totals.marginAmount,
        marginTargetPercent: number(quote.marginPercent),
        actualMarginPercent: totals.actualMarginPercent,
        suggestedPrice: totals.suggestedPrice,
        finalPrice: totals.finalPrice,
        hasManualProposedPrice: totals.hasManualProposedPrice,
      },
    };
  }

  async function handleLogin(event) {
    event.preventDefault();
    setLoading(true);
    setStatus("");
    try {
      const session = await authApi.login(credentials);
      setSession(session);
      setUser(session.user);
      setStatus("Sesion iniciada correctamente.");
    } catch (error) {
      setStatus(error.message);
    } finally {
      setLoading(false);
    }
  }

  function handleLogout() {
    clearSession();
    setUser(null);
    setQuotes([]);
    setStatus("Sesion cerrada.");
  }

  async function handleCreateQuote(event) {
    event.preventDefault();
    if (!validateQuoteBeforeExport()) return;

    setLoading(true);
    setStatus("");
    try {
      if (editingQuoteId) {
        const response = await quotesApi.update(editingQuoteId, buildQuotePayload());
        setSelectedHistoryQuote(response.quote);
        setEditingQuoteId("");
        setStatus("Cotizacion actualizada correctamente.");
      } else {
        await quotesApi.create(buildQuotePayload());
        setStatus("Cotizacion guardada en el historial.");
      }
      await loadPrivateData();
    } catch (error) {
      setStatus(handleApiError(error));
    } finally {
      setLoading(false);
    }
  }

  async function handleViewQuote(id) {
    setLoading(true);
    setStatus("");
    try {
      const response = await quotesApi.get(id);
      setSelectedHistoryQuote(response.quote);
      setStatus(`Cotizacion No. ${response.quote.quote_number} cargada para consulta.`);
    } catch (error) {
      setStatus(handleApiError(error));
    } finally {
      setLoading(false);
    }
  }

  async function handleEditQuote(id) {
    setLoading(true);
    setStatus("");
    try {
      const response = await quotesApi.get(id);
      setSelectedHistoryQuote(response.quote);
      mapStoredQuoteToForm(response.quote);
      setEditingQuoteId(response.quote.id);
      window.location.hash = "cotizador";
      setStatus(`Editando cotizacion No. ${response.quote.quote_number}.`);
    } catch (error) {
      setStatus(handleApiError(error));
    } finally {
      setLoading(false);
    }
  }

  async function handleDeleteQuote(item) {
    const confirmed = window.confirm(`Eliminar la cotizacion No. ${item.quote_number} de ${item.customer_name}?`);
    if (!confirmed) return;

    setLoading(true);
    setStatus("");
    try {
      await quotesApi.remove(item.id);
      if (selectedHistoryQuote?.id === item.id) {
        setSelectedHistoryQuote(null);
      }
      if (editingQuoteId === item.id) {
        setEditingQuoteId("");
      }
      setStatus(`Cotizacion No. ${item.quote_number} eliminada.`);
      await loadPrivateData();
    } catch (error) {
      setStatus(handleApiError(error));
    } finally {
      setLoading(false);
    }
  }

  async function handleExportPdf() {
    if (!validateQuoteBeforeExport()) return;

    setLoading(true);
    setStatus("");
    try {
      const { blob, filename } = await quotesApi.exportPdf(buildQuotePayload());
      const url = URL.createObjectURL(blob);
      const link = document.createElement("a");
      link.href = url;
      link.download = filename || "cotizacion.pdf";
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.setTimeout(() => URL.revokeObjectURL(url), 1000);
      setStatus(`Cotizacion PDF generada correctamente: ${filename || "cotizacion.pdf"}`);
      await loadPrivateData();
    } catch (error) {
      setStatus(handleApiError(error));
    } finally {
      setLoading(false);
    }
  }

  return (
    <main className={`app-shell ${sidebarOpen ? "" : "sidebar-collapsed"}`}>
      <button
        type="button"
        className="sidebar-toggle floating"
        onClick={() => setSidebarOpen(true)}
        aria-label="Mostrar menu"
      >
        Menu
      </button>

      <aside className="sidebar">
        <div>
          <div className="sidebar-head">
            <div>
              <p className="eyebrow">GCM Transportes</p>
              <h1>Cotizador empresarial</h1>
            </div>
            <button
              type="button"
              className="sidebar-toggle"
              onClick={() => setSidebarOpen(false)}
              aria-label="Ocultar menu"
            >
              Ocultar
            </button>
          </div>
        </div>

        <nav className="nav-list" aria-label="Secciones principales">
          <a href="#dashboard">Dashboard</a>
              <a href="#cotizador">Cotizador</a>
              <a href="#combustible">Combustible</a>
          <a href="#historial">Historial</a>
        </nav>

        <div className="session-box">
          {user ? (
            <>
              <span>{user.name}</span>
              <strong>{user.role}</strong>
              <button type="button" onClick={handleLogout}>Cerrar sesion</button>
            </>
          ) : (
            <span>Acceso seguro con JWT</span>
          )}
        </div>
      </aside>

      <section className="workspace">
        <header className="topbar">
          <div>
            <p className="eyebrow">Operacion comercial</p>
            <h2>Modelo de Costo de Transporte</h2>
          </div>
        </header>

        {status && <div className="notice">{status}</div>}

        {!user && (
          <section className="panel login-panel">
            <div>
              <p className="eyebrow">Ingreso</p>
              <h3>Iniciar sesion</h3>
              <p className="muted">Usa el usuario inicial despues de ejecutar la migracion y el seed.</p>
            </div>
            <form onSubmit={handleLogin} className="form-grid compact">
              <label>
                Email
                <input
                  type="email"
                  value={credentials.email}
                  onChange={(event) => setCredentials({ ...credentials, email: event.target.value })}
                />
              </label>
              <label>
                Password
                <input
                  type="password"
                  value={credentials.password}
                  onChange={(event) => setCredentials({ ...credentials, password: event.target.value })}
                />
              </label>
              <button type="submit" disabled={loading}>{loading ? "Validando..." : "Entrar"}</button>
            </form>
          </section>
        )}

        <section id="dashboard" className="metrics-grid">
          <article className="metric">
            <span>Cotizaciones</span>
            <strong>{summary.total_quotes}</strong>
          </article>
          <article className="metric">
            <span>Total cotizado</span>
            <strong>{currency(summary.total_quoted)}</strong>
          </article>
          <article className="metric">
            <span>Margen promedio</span>
            <strong>{Number(summary.average_margin || 0).toFixed(1)}%</strong>
          </article>
        </section>

        <section id="cotizador" className="panel quote-layout">
          <form onSubmit={handleCreateQuote} className="form-grid">
            <div className="section-heading">
              <p className="eyebrow">{editingQuoteId ? "Editar cotizacion" : "Nueva cotizacion"}</p>
              <h3>Datos que ingresa el usuario</h3>
            </div>

            <label>
              Nombre Cliente
              <input value={quote.customerName} onChange={(event) => updateQuote("customerName", event.target.value)} required />
            </label>
            <label>
              RUC
              <input value={quote.customerRuc} onChange={(event) => updateQuote("customerRuc", event.target.value)} />
            </label>
            <label>
              Telefono
              <input value={quote.customerPhone} onChange={(event) => updateQuote("customerPhone", event.target.value)} />
            </label>

            <label>
              Volumen a trasladar glns
              <input type="number" min="1" value={quote.volumeGallons} onChange={(event) => updateQuote("volumeGallons", event.target.value)} required />
            </label>
            <label>
              Distancia recorrida km
              <input type="number" min="1" step="0.01" value={quote.distanceKm} onChange={(event) => updateQuote("distanceKm", event.target.value)} required />
            </label>
            <label>
              Rendimiento km/galon
              <select value={quote.fuelEfficiencyKmPerGallon} onChange={(event) => updateQuote("fuelEfficiencyKmPerGallon", event.target.value)}>
                {Array.from({ length: 8 }, (_, index) => index + 8).map((value) => (
                  <option key={value} value={value}>{value} km/galon</option>
                ))}
              </select>
            </label>
            <label>
              Margen objetivo %
              <input type="number" min="0" max="70" step="0.01" value={quote.marginPercent} onChange={(event) => updateQuote("marginPercent", event.target.value)} required />
            </label>
            <label>
              Propuesto C$
              <input type="number" min="0" step="0.01" value={quote.proposedPrice} placeholder={totals.suggestedPrice.toFixed(2)} onChange={(event) => updateQuote("proposedPrice", event.target.value)} />
            </label>

            <div className="section-heading horizontal collapsible-heading">
              <div>
                <p className="eyebrow">Variables editables por defecto</p>
                <h3>Costos operativos</h3>
              </div>
              <button
                type="button"
                className="secondary-action"
                onClick={() => setShowOperatingVars((current) => !current)}
              >
                <Icon name="collapse" />
                {showOperatingVars ? "Colapsar" : "Mostrar"}
              </button>
            </div>

            {showOperatingVars && (
              <>
                <label>
                  Conductor %
                  <input type="number" min="0" max="50" step="0.01" value={quote.driverPercent} onChange={(event) => updateQuote("driverPercent", event.target.value)} required />
                </label>
                <label>
                  Viatico C$
                  <input type="number" min="0" step="0.01" value={quote.travelAllowance} onChange={(event) => updateQuote("travelAllowance", event.target.value)} required />
                </label>
                <label>
                  ADMON %
                  <input type="number" min="0" max="50" step="0.01" value={quote.adminPercent} onChange={(event) => updateQuote("adminPercent", event.target.value)} required />
                </label>

                <div className="fixed-costs">
                  <span>Fijos: tasa de cambio C$36.6243/US$, depreciacion US$0.081/km, mantenimiento US$0.13/km, llantas US$0.05/km, Alcaldia 1%, DGI 3%. El conductor se calcula como porcentaje del total del viaje multiplicado por el indice laboral.</span>
                </div>
              </>
            )}

            <div className="form-actions">
              <button type="submit" disabled={!user || loading}>
                <Icon name="save" />
                {user ? (editingQuoteId ? "Actualizar cotizacion" : "Guardar cotizacion") : "Inicia sesion para guardar"}
              </button>
              {editingQuoteId && (
                <button type="button" className="secondary-action" onClick={cancelEditing}>
                  <Icon name="cancel" />
                  Cancelar edicion
                </button>
              )}
              <button type="button" className="secondary-action" onClick={handleExportPdf} disabled={!user || loading}>
                <Icon name="pdf" />
                Generar PDF
              </button>
            </div>
          </form>

          <aside className="quote-summary">
            <p className="eyebrow">Resultado</p>
            <div>
              <span>Galones consumidos</span>
              <strong>{totals.gallonsConsumed.toFixed(2)}</strong>
            </div>
            <div>
              <span>Costo del viaje</span>
              <strong>{currency(totals.tripCost)}</strong>
            </div>
            <div>
              <span>Precio sugerido</span>
              <strong>{currency(totals.suggestedPrice)}</strong>
            </div>
            <div>
              <span>Propuesto final</span>
              <strong>{currency(totals.finalPrice)}</strong>
            </div>
            <div>
              <span>Margen real</span>
              <strong>{totals.actualMarginPercent.toFixed(2)}%</strong>
            </div>
            <div>
              <span>Flete por galon</span>
              <strong>{currency(totals.freightPerGallon)}</strong>
            </div>
          </aside>
        </section>

        <section id="combustible" className="panel fuel-layout">
          <form className="form-grid">
            <div className="section-heading">
              <p className="eyebrow">Precio combustible</p>
              <h3>Modelo de precio al cliente</h3>
            </div>

            <label>
              Producto
              <select value={quote.productId} onChange={(event) => updateQuote("productId", event.target.value)}>
                {fuelProducts.map((product) => (
                  <option key={product.id} value={product.id}>{product.name}</option>
                ))}
              </select>
            </label>
            <label>
              Precio de referencia C$/gl
              <input type="number" min="0" step="0.0001" value={fuelPricing.referencePrice} onChange={(event) => updateFuelPricing("referencePrice", event.target.value)} />
            </label>
            <label>
              Descuento C$/gl
              <input type="number" min="0" step="0.0001" value={fuelPricing.discount} onChange={(event) => updateFuelPricing("discount", event.target.value)} />
            </label>
            <label>
              Comision vendedor C$/gl
              <input type="number" min="0" step="0.0001" value={fuelPricing.sellerCommission} onChange={(event) => updateFuelPricing("sellerCommission", event.target.value)} />
            </label>
            <div className="fixed-costs">
              <span>Precio cliente = precio de referencia menos descuento. Margen neto = margen bruto menos comision del vendedor y flete por galon calculado en el modelo cotizador.</span>
            </div>
          </form>

          <aside className="quote-summary">
            <p className="eyebrow">Resultado combustible</p>
            <div>
              <span>Precio cliente</span>
              <strong>{currency(fuelTotals.customerPrice)}</strong>
            </div>
            <div>
              <span>Margen bruto</span>
              <strong>{currency(fuelTotals.grossMargin)}</strong>
            </div>
            <div>
              <span>Flete del cotizador</span>
              <strong>{currency(totals.freightPerGallon)}</strong>
            </div>
            <div>
              <span>Margen neto</span>
              <strong>{currency(fuelTotals.netMargin)}</strong>
            </div>
          </aside>
        </section>

        <section className="panel">
          <div className="section-heading">
            <p className="eyebrow">Desglose</p>
            <h3>Calculo del viaje en cordobas</h3>
          </div>
          <div className="cost-grid">
            <span>Conductor ({number(quote.driverPercent).toFixed(2)}% x {fixedFactors.laborIndex})</span><strong>{dualCurrency(totals.driverCost)}</strong>
            <span>Depreciacion</span><strong>{dualCurrency(totals.depreciation)}</strong>
            <span>Mantenimiento</span><strong>{dualCurrency(totals.maintenance)}</strong>
            <span>Gastos de llantas</span><strong>{dualCurrency(totals.tires)}</strong>
            <span>Viatico</span><strong>{dualCurrency(quote.travelAllowance)}</strong>
            <span>ADMON</span><strong>{dualCurrency(totals.adminCost)}</strong>
            <span>Alcaldia de Managua</span><strong>{dualCurrency(totals.municipalityCost)}</strong>
            <span>DGI</span><strong>{dualCurrency(totals.dgiCost)}</strong>
            <span>Margen real</span><strong>{dualCurrency(totals.marginAmount)}</strong>
          </div>
        </section>

        <section id="historial" className="panel">
          <div className="section-heading horizontal">
            <div>
              <p className="eyebrow">Historial</p>
              <h3>Ultimas cotizaciones</h3>
            </div>
            <button type="button" onClick={loadPrivateData} disabled={!user}>Actualizar</button>
          </div>

          <div className="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Cliente</th>
                  <th>Volumen</th>
                  <th>Distancia</th>
                  <th>Rend.</th>
                  <th>Precio</th>
                  <th>Flete/gl</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                {quotes.length === 0 ? (
                  <tr><td colSpan="8">Sin cotizaciones guardadas todavia.</td></tr>
                ) : (
                  quotes.map((item) => (
                    <tr key={item.id}>
                      <td>{item.quote_number}</td>
                      <td>{item.customer_name}</td>
                      <td>{Number(item.volume_gallons || 0).toLocaleString("es-NI")} glns</td>
                      <td>{Number(item.distance_km || 0).toLocaleString("es-NI")} km</td>
                      <td>{Number(item.fuel_efficiency_km_per_gallon || 0).toLocaleString("es-NI")} km/gl</td>
                      <td>{currency(item.final_price)}</td>
                      <td>{currency(item.freight_per_gallon)}</td>
                      <td>
                        <div className="actions-menu">
                          <button
                            type="button"
                            className="kebab-action"
                            onClick={() => setOpenActionMenuId((current) => (current === item.id ? "" : item.id))}
                            disabled={loading}
                            title="Acciones"
                            aria-label={`Acciones de cotizacion ${item.quote_number}`}
                            aria-expanded={openActionMenuId === item.id}
                          >
                            <Icon name="more" />
                          </button>
                          {openActionMenuId === item.id && (
                            <div className="actions-dropdown">
                              <div className="actions-status">
                                <span className="status-dot" />
                                Cotizacion guardada
                              </div>
                              <button
                                type="button"
                                onClick={() => {
                                  setOpenActionMenuId("");
                                  handleViewQuote(item.id);
                                }}
                              >
                                <Icon name="view" />
                                Ver detalle
                              </button>
                              <button
                                type="button"
                                onClick={() => {
                                  setOpenActionMenuId("");
                                  handleEditQuote(item.id);
                                }}
                              >
                                <Icon name="edit" />
                                Editar
                              </button>
                              <button
                                type="button"
                                className="danger"
                                onClick={() => {
                                  setOpenActionMenuId("");
                                  handleDeleteQuote(item);
                                }}
                              >
                                <Icon name="delete" />
                                Eliminar
                              </button>
                            </div>
                          )}
                        </div>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>

          {selectedHistoryQuote && (
            <div className="history-detail">
              <div className="section-heading horizontal">
                <div>
                  <p className="eyebrow">Consulta de cotizacion</p>
                  <h3>No. {selectedHistoryQuote.quote_number} - {selectedHistoryQuote.customer_name}</h3>
                </div>
                <div className="row-actions">
                  <button type="button" className="secondary-action" onClick={() => handleEditQuote(selectedHistoryQuote.id)} disabled={loading}>
                    <Icon name="edit" />
                    Editar
                  </button>
                  <button type="button" className="secondary-action" onClick={() => setSelectedHistoryQuote(null)}>
                    <Icon name="cancel" />
                    Cerrar
                  </button>
                </div>
              </div>

              <div className="detail-grid">
                <div><span>Cliente</span><strong>{selectedHistoryQuote.customer_name}</strong></div>
                <div><span>RUC</span><strong>{selectedHistoryQuote.customer_ruc || "-"}</strong></div>
                <div><span>Telefono</span><strong>{selectedHistoryQuote.customer_phone || "-"}</strong></div>
                <div><span>Producto</span><strong>{selectedQuoteBreakdown.productName || "Diesel"}</strong></div>
                <div><span>Volumen</span><strong>{Number(selectedHistoryQuote.volume_gallons || 0).toLocaleString("es-NI")} glns</strong></div>
                <div><span>Distancia</span><strong>{Number(selectedHistoryQuote.distance_km || 0).toLocaleString("es-NI")} km</strong></div>
                <div><span>Precio combustible cliente</span><strong>{currency(selectedQuoteBreakdown.fuelCustomerPricePerGallon)}</strong></div>
                <div><span>Total combustible</span><strong>{currency(selectedQuoteBreakdown.fuelCustomerTotal)}</strong></div>
                <div><span>Flete total</span><strong>{currency(selectedHistoryQuote.final_price)}</strong></div>
                <div><span>Flete por galon</span><strong>{currency(selectedHistoryQuote.freight_per_gallon)}</strong></div>
                <div><span>Margen</span><strong>{Number(selectedHistoryQuote.margin_percent || 0).toFixed(2)}%</strong></div>
                <div><span>Actualizada</span><strong>{selectedHistoryQuote.updated_at ? new Date(selectedHistoryQuote.updated_at).toLocaleString("es-NI") : "-"}</strong></div>
              </div>
            </div>
          )}
        </section>
      </section>
    </main>
  );
}

export default App;
